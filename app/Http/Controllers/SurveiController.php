<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Requests\StoreSurveiRequest;
use App\Http\Requests\UpdateSurveiRequest;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use App\Models\Survei;
use App\Models\SurveiFoto;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SurveiController extends Controller
{
    /**
     * Display a listing of all survei.
     */
    public function index(Request $request)
    {
        $role  = auth()->user()->role;
        $query = Survei::with(['pengajuan.penerima', 'pengajuan.petugas'])->latest();

        // Petugas only sees their own
        if ($role === 'petugas') {
            $query->whereHas('pengajuan', function ($q) {
                $q->where('petugas_id', auth()->id());
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('pengajuan', function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('penerima', fn($qp) =>
                      $qp->where('nama', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%")
                  );
            });
        }

        // Status filter (status of the related pengajuan)
        if ($request->filled('status')) {
            $query->whereHas('pengajuan', fn($q) =>
                $q->where('status', $request->input('status'))
            );
        }

        // Date filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->input('end_date'));
        }

        $surveiList = $query->paginate(10)->withQueryString();

        return view('survei.index', compact('surveiList'));
    }

    /**
     * Show the form for creating a new survei.
     * Route: GET /survei/create?pengajuan_id=X  OR  GET /survei/{pengajuan}/create (handled by custom route)
     * We pass pengajuan_id via query string.
     */
    public function create(Request $request)
    {
        if (auth()->user()->role !== 'petugas') {
            abort(403, 'Hanya Petugas yang dapat mengisi survei.');
        }

        $pengajuan = Pengajuan::with(['penerima', 'petugas', 'jenisBantuan', 'survei'])
            ->findOrFail($request->query('pengajuan_id'));

        // Only owning petugas
        if ($pengajuan->petugas_id !== auth()->id()) {
            abort(403, 'Anda bukan petugas yang menangani pengajuan ini.');
        }

        // Status must be menunggu_survei
        if ($pengajuan->status !== 'menunggu_survei') {
            return redirect()->route('pengajuan.show', $pengajuan)
                ->with('error', 'Survei hanya dapat diisi jika status pengajuan adalah "Menunggu Survei".');
        }

        // Prevent duplicate survei
        if ($pengajuan->survei) {
            return redirect()->route('survei.show', $pengajuan->survei)
                ->with('info', 'Survei untuk pengajuan ini sudah ada.');
        }

        return view('survei.create', compact('pengajuan'));
    }

    /**
     * Store a newly created survei in storage.
     */
    public function store(StoreSurveiRequest $request)
    {
        $pengajuan = Pengajuan::with('survei')
            ->findOrFail($request->input('pengajuan_id'));

        // Guard: status harus menunggu_survei
        if ($pengajuan->status !== 'menunggu_survei') {
            return back()->with('error', 'Pengajuan ini tidak berstatus menunggu survei.');
        }

        // Guard: ownership
        if ($pengajuan->petugas_id !== auth()->id()) {
            abort(403, 'Anda bukan petugas yang menangani pengajuan ini.');
        }

        // Guard: no duplicate
        if ($pengajuan->survei) {
            return redirect()->route('survei.show', $pengajuan->survei)
                ->with('info', 'Survei sudah ada.');
        }

        DB::transaction(function () use ($request, $pengajuan) {
            // 1. Simpan Survei
            $survei = Survei::create([
                'pengajuan_id'      => $pengajuan->id,
                'status_rumah'      => $request->status_rumah,
                'kepemilikan_rumah' => $request->kepemilikan_rumah,
                'jenis_lantai'      => $request->jenis_lantai,
                'jenis_dinding'     => $request->jenis_dinding,
                'jenis_atap'        => $request->jenis_atap,
                'jumlah_kamar'      => $request->jumlah_kamar,
                'jumlah_penghuni'   => $request->jumlah_penghuni,
                'pekerjaan'         => $request->pekerjaan,
                'penghasilan'       => $request->penghasilan,
                'jumlah_tanggungan' => $request->jumlah_tanggungan,
                'punya_motor'       => $request->boolean('punya_motor'),
                'punya_mobil'       => $request->boolean('punya_mobil'),
                'punya_sawah'       => $request->boolean('punya_sawah'),
                'punya_ternak'      => $request->boolean('punya_ternak'),
                'catatan'           => $request->catatan,
            ]);

            // 2. Simpan Foto (wajib 3 + 2 opsional)
            $fotoKategori = [
                'foto_tampak_depan' => 'Tampak Depan Rumah',
                'foto_ruang_tamu'   => 'Ruang Tamu',
                'foto_dapur'        => 'Dapur',
                'foto_kamar'        => 'Kamar',
                'foto_kamar_mandi'  => 'Kamar Mandi',
            ];

            foreach ($fotoKategori as $field => $label) {
                if ($request->hasFile($field)) {
                    $file     = $request->file($field);
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('survei', $filename, 'public');

                    SurveiFoto::create([
                        'survei_id' => $survei->id,
                        'kategori'  => $label,
                        'file'      => 'survei/' . $filename,
                    ]);
                }
            }

            // 3. Simpan Dokumen
            if ($request->has('dokumen') && is_array($request->dokumen)) {
                foreach ($request->dokumen as $index => $doc) {
                    if (isset($request->file('dokumen')[$index]['file'])) {
                        $file     = $request->file('dokumen')[$index]['file'];
                        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $file->storeAs('dokumen', $filename, 'public');

                        Dokumen::create([
                            'pengajuan_id' => $pengajuan->id,
                            'nama_dokumen' => $doc['nama'],
                            'file'         => 'dokumen/' . $filename,
                        ]);
                    }
                }
            }

            // 4. Update Status Pengajuan
            $pengajuan->update(['status' => 'menunggu_verifikasi']);

            // 5. Simpan Riwayat Status
            RiwayatStatus::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id'      => auth()->id(),
                'status'       => 'menunggu_verifikasi',
                'catatan'      => 'Survei lapangan selesai dilakukan.',
            ]);
        });

        ActivityLogger::log("Mengisi Survei: {$pengajuan->kode_pengajuan}");

        return redirect()->route('survei.index')
            ->with('success', 'Survei berhasil disimpan. Status pengajuan diperbarui menjadi Menunggu Verifikasi.');
    }

    /**
     * Display the specified survei.
     */
    public function show(Survei $survei)
    {
        $role = auth()->user()->role;

        // Petugas hanya bisa melihat survei miliknya
        if ($role === 'petugas' && $survei->pengajuan->petugas_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses melihat survei ini.');
        }

        $survei->load([
            'pengajuan.penerima',
            'pengajuan.petugas',
            'pengajuan.jenisBantuan',
            'pengajuan.dokumen',
            'pengajuan.riwayatStatus.user',
            'foto',
        ]);

        return view('survei.show', compact('survei'));
    }

    /**
     * Show the form for editing the specified survei.
     */
    public function edit(Survei $survei)
    {
        $pengajuan = $survei->pengajuan;
        $editableStatuses = ['menunggu_verifikasi', 'revisi_survei'];

        // Authorization
        if (auth()->user()->role !== 'petugas'
            || $pengajuan->petugas_id !== auth()->id()
            || ! in_array($pengajuan->status, $editableStatuses)
        ) {
            abort(403, 'Survei tidak dapat diubah.');
        }

        $survei->load('foto', 'pengajuan.penerima', 'pengajuan.dokumen');

        return view('survei.edit', compact('survei', 'pengajuan'));
    }

    /**
     * Update the specified survei in storage.
     */
    public function update(UpdateSurveiRequest $request, Survei $survei)
    {
        $pengajuan = $survei->pengajuan;
        $statusChanged = false;

        DB::transaction(function () use ($request, $survei, $pengajuan, &$statusChanged) {
            // 1. Update data survei
            $survei->update([
                'status_rumah'      => $request->status_rumah,
                'kepemilikan_rumah' => $request->kepemilikan_rumah,
                'jenis_lantai'      => $request->jenis_lantai,
                'jenis_dinding'     => $request->jenis_dinding,
                'jenis_atap'        => $request->jenis_atap,
                'jumlah_kamar'      => $request->jumlah_kamar,
                'jumlah_penghuni'   => $request->jumlah_penghuni,
                'pekerjaan'         => $request->pekerjaan,
                'penghasilan'       => $request->penghasilan,
                'jumlah_tanggungan' => $request->jumlah_tanggungan,
                'punya_motor'       => $request->boolean('punya_motor'),
                'punya_mobil'       => $request->boolean('punya_mobil'),
                'punya_sawah'       => $request->boolean('punya_sawah'),
                'punya_ternak'      => $request->boolean('punya_ternak'),
                'catatan'           => $request->catatan,
            ]);

            // 2. Replace foto jika ada yang baru diupload
            $fotoKategori = [
                'foto_tampak_depan' => 'Tampak Depan Rumah',
                'foto_ruang_tamu'   => 'Ruang Tamu',
                'foto_dapur'        => 'Dapur',
                'foto_kamar'        => 'Kamar',
                'foto_kamar_mandi'  => 'Kamar Mandi',
            ];

            foreach ($fotoKategori as $field => $label) {
                if ($request->hasFile($field)) {
                    // Hapus foto lama jika ada untuk kategori ini
                    $existing = $survei->foto()->where('kategori', $label)->first();
                    if ($existing) {
                        Storage::disk('public')->delete($existing->file);
                        $existing->delete();
                    }

                    $file     = $request->file($field);
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('survei', $filename, 'public');

                    SurveiFoto::create([
                        'survei_id' => $survei->id,
                        'kategori'  => $label,
                        'file'      => 'survei/' . $filename,
                    ]);
                }
            }

            // 3. Tambah dokumen baru jika ada
            if ($request->has('dokumen') && is_array($request->dokumen)) {
                foreach ($request->dokumen as $index => $doc) {
                    if (isset($request->file('dokumen')[$index]['file'])) {
                        $file     = $request->file('dokumen')[$index]['file'];
                        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $file->storeAs('dokumen', $filename, 'public');

                        Dokumen::create([
                            'pengajuan_id' => $pengajuan->id,
                            'nama_dokumen' => $doc['nama'],
                            'file'         => 'dokumen/' . $filename,
                        ]);
                    }
                }
            }

            if ($pengajuan->status === 'revisi_survei') {
                $pengajuan->update(['status' => 'menunggu_verifikasi']);
                $statusChanged = true;

                RiwayatStatus::create([
                    'pengajuan_id' => $pengajuan->id,
                    'user_id'      => auth()->id(),
                    'status'       => 'menunggu_verifikasi',
                    'catatan'      => 'Survei direvisi dan dikirim ulang untuk verifikasi.',
                ]);
            }
        });

        ActivityLogger::log("Edit Survei: {$pengajuan->kode_pengajuan}");

        $message = 'Data survei berhasil diperbarui.';
        if ($statusChanged) {
            $message = 'Data survei berhasil diperbarui. Status pengajuan diperbarui menjadi Menunggu Verifikasi.';
        }

        return redirect()->route('survei.show', $survei)
            ->with('success', $message);
    }

    /**
     * Remove a dokumen from the pengajuan.
     */
    public function destroyDokumen(Dokumen $dokumen)
    {
        $pengajuan = $dokumen->pengajuan;

        if (auth()->user()->role !== 'petugas'
            || $pengajuan->petugas_id !== auth()->id()
            || $pengajuan->status !== 'menunggu_verifikasi'
        ) {
            abort(403, 'Dokumen tidak dapat dihapus.');
        }

        Storage::disk('public')->delete($dokumen->file);
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }
}
