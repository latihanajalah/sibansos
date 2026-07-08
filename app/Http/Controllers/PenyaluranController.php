<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Requests\StorePenyaluranRequest;
use App\Models\Pengajuan;
use App\Models\Penyaluran;
use App\Models\Dokumen;
use App\Models\RiwayatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PenyaluranController extends Controller
{
    /**
     * Display a listing of penyaluran.
     */
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $query = Penyaluran::with(['pengajuan.penerima', 'petugas'])->latest();

        // Petugas only sees their own penyaluran
        if ($role === 'petugas') {
            $query->where('petugas_id', auth()->id());
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('pengajuan', function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('penerima', fn ($qp) =>
                      $qp->where('nama', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%")
                  );
            });
        }

        // Date Filter
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->input('end_date'));
        }

        $penyaluranList = $query->paginate(10)->withQueryString();

        return view('penyaluran.index', compact('penyaluranList'));
    }

    /**
     * Show the form for creating a new penyaluran.
     */
    public function create(Request $request)
    {
        if (auth()->user()->role !== 'petugas') {
            abort(403, 'Hanya Petugas Lapangan yang dapat menyalurkan bantuan.');
        }

        $pengajuan = Pengajuan::with(['penerima', 'jenisBantuan'])
            ->findOrFail($request->query('pengajuan_id'));

        // Guard: status must be siap_disalurkan
        if ($pengajuan->status !== 'siap_disalurkan') {
            return redirect()->route('pengajuan.show', $pengajuan)
                ->with('error', 'Penyaluran hanya dapat diproses untuk pengajuan yang telah disetujui pimpinan.');
        }

        return view('penyaluran.create', compact('pengajuan'));
    }

    /**
     * Store a newly logged penyaluran.
     */
    public function store(StorePenyaluranRequest $request)
    {
        $pengajuan = Pengajuan::findOrFail($request->input('pengajuan_id'));

        // Guard: status must be siap_disalurkan
        if ($pengajuan->status !== 'siap_disalurkan') {
            return back()->with('error', 'Status pengajuan tidak valid untuk penyaluran.');
        }

        DB::transaction(function () use ($request, $pengajuan) {
            // 1. Simpan penyaluran
            Penyaluran::create([
                'pengajuan_id' => $pengajuan->id,
                'petugas_id'   => auth()->id(),
                'tanggal'      => $request->tanggal,
                'status'       => 'selesai',
                'catatan'      => $request->catatan,
            ]);

            // 2. Simpan upload bukti ke tabel dokumen
            if ($request->hasFile('bukti')) {
                foreach ($request->file('bukti') as $file) {
                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('dokumen', $filename, 'public');

                    Dokumen::create([
                        'pengajuan_id' => $pengajuan->id,
                        'nama_dokumen' => 'Bukti Penyaluran',
                        'file'         => 'dokumen/' . $filename,
                    ]);
                }
            }

            // 3. Update status pengajuan menjadi selesai
            $pengajuan->update(['status' => 'selesai']);

            // 4. Simpan riwayat status
            RiwayatStatus::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id'      => auth()->id(),
                'status'       => 'selesai',
                'catatan'      => 'Bantuan telah disalurkan.',
            ]);
        });

        ActivityLogger::log("Penyaluran Bantuan: untuk pengajuan {$pengajuan->kode_pengajuan}");

        return redirect()->route('penyaluran.index')
            ->with('success', 'Penyaluran bantuan berhasil dicatat and status diselesaikan.');
    }

    /**
     * Display details of a logged penyaluran.
     */
    public function show(Penyaluran $penyaluran)
    {
        $role = auth()->user()->role;

        // Petugas only sees their own penyaluran
        if ($role === 'petugas' && $penyaluran->petugas_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses melihat data penyaluran ini.');
        }

        $penyaluran->load([
            'pengajuan.penerima',
            'pengajuan.jenisBantuan',
            'pengajuan.dokumen',
            'pengajuan.riwayatStatus.user',
            'petugas',
        ]);

        return view('penyaluran.show', compact('penyaluran'));
    }
}
