<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Models\Pengajuan;
use App\Models\Penerima;
use App\Models\JenisBantuan;
use App\Http\Requests\StorePengajuanRequest;
use App\Http\Requests\UpdatePengajuanRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        $query = Pengajuan::with(['penerima', 'petugas', 'jenisBantuan'])->latest();

        // Role-based visibility
        if ($role === 'petugas') {
            $query->where('petugas_id', auth()->id());
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('penerima', function ($qp) use ($search) {
                      $qp->where('nama', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%");
                  })
                  ->orWhereHas('petugas', function ($qu) use ($search) {
                      $qu->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Date filters
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pengajuan', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_pengajuan', '<=', $request->input('end_date'));
        }

        $pengajuanList = $query->paginate(10)->withQueryString();

        return view('pengajuan.index', compact('pengajuanList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->user()->role !== 'petugas') {
            abort(403, 'Hanya Petugas Lapangan yang dapat membuat pengajuan.');
        }

        $penerimaList = Penerima::orderBy('nama')->get();
        $jenisBantuanList = JenisBantuan::where('status', true)->orderBy('nama_bantuan')->get();

        return view('pengajuan.create', compact('penerimaList', 'jenisBantuanList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePengajuanRequest $request)
    {
        // Generate unique code PGJ-YYYYMMDD-0001
        $today = today();
        $prefix = 'PGJ-' . $today->format('Ymd') . '-';
        
        $kode = DB::transaction(function () use ($request, $today, $prefix) {
            // Lock table row to prevent race conditions during code generation
            $lastPengajuan = Pengajuan::whereDate('tanggal_pengajuan', $today)
                ->where('kode_pengajuan', 'like', $prefix . '%')
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first();

            $seq = 1;
            if ($lastPengajuan) {
                $lastSeq = (int) substr($lastPengajuan->kode_pengajuan, -4);
                $seq = $lastSeq + 1;
            }
            $generatedCode = $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);

            $pengajuan = Pengajuan::create([
                'kode_pengajuan'    => $generatedCode,
                'penerima_id'       => $request->penerima_id,
                'petugas_id'        => auth()->id(),
                'tanggal_pengajuan' => $today,
                'status'            => 'menunggu_survei',
                'keterangan'        => $request->keterangan,
            ]);

            $pengajuan->jenisBantuan()->sync($request->jenis_bantuan_ids);

            return $generatedCode;
        });

        ActivityLogger::log("Membuat Pengajuan: {$kode}");

        return redirect()->route('pengajuan.index')
            ->with('success', "Pengajuan dengan kode {$kode} berhasil dibuat.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengajuan $pengajuan)
    {
        // Authorization check for Petugas role
        if (auth()->user()->role === 'petugas' && $pengajuan->petugas_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses melihat pengajuan ini.');
        }

        $pengajuan->load(['penerima', 'petugas', 'jenisBantuan', 'riwayatStatus']);

        return view('pengajuan.show', compact('pengajuan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengajuan $pengajuan)
    {
        if (auth()->user()->role !== 'petugas' || $pengajuan->petugas_id !== auth()->id() || $pengajuan->status !== 'menunggu_survei') {
            abort(403, 'Pengajuan tidak dapat diubah.');
        }

        $penerimaList = Penerima::orderBy('nama')->get();
        $jenisBantuanList = JenisBantuan::where('status', true)->orderBy('nama_bantuan')->get();

        return view('pengajuan.edit', compact('pengajuan', 'penerimaList', 'jenisBantuanList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePengajuanRequest $request, Pengajuan $pengajuan)
    {
        DB::transaction(function () use ($request, $pengajuan) {
            $pengajuan->update([
                'penerima_id' => $request->penerima_id,
                'keterangan'  => $request->keterangan,
            ]);

            $pengajuan->jenisBantuan()->sync($request->jenis_bantuan_ids);
        });

        ActivityLogger::log("Mengedit Pengajuan: {$pengajuan->kode_pengajuan}");

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        if (auth()->user()->role !== 'petugas' || $pengajuan->petugas_id !== auth()->id() || $pengajuan->status !== 'menunggu_survei') {
            abort(403, 'Pengajuan tidak dapat dihapus.');
        }

        $kode = $pengajuan->kode_pengajuan;
        $pengajuan->delete();

        ActivityLogger::log("Menghapus Pengajuan: {$kode}");

        return redirect()->route('pengajuan.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }
}
