<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Requests\VerifikasiRequest;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifikasiController extends Controller
{
    /**
     * Status map: keputusan → status baru
     */
    private array $statusMap = [
        'setujui' => 'menunggu_persetujuan',
        'revisi'  => 'revisi_survei',
        'tolak'   => 'ditolak_admin',
    ];

    /**
     * Display listing of pengajuan menunggu verifikasi.
     */
    public function index(Request $request)
    {
        $query = Pengajuan::with(['penerima', 'petugas', 'survei'])
            ->whereIn('status', ['menunggu_verifikasi', 'revisi_survei', 'menunggu_persetujuan', 'ditolak_admin'])
            ->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode_pengajuan', 'like', "%{$search}%")
                  ->orWhereHas('penerima', fn ($qp) =>
                      $qp->where('nama', 'like', "%{$search}%")
                         ->orWhere('nik', 'like', "%{$search}%")
                  );
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Petugas filter
        if ($request->filled('petugas_id')) {
            $query->where('petugas_id', $request->input('petugas_id'));
        }

        // Date filter (based on survei's created_at)
        if ($request->filled('start_date')) {
            $query->whereHas('survei', function ($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->input('start_date'));
            });
        }
        if ($request->filled('end_date')) {
            $query->whereHas('survei', function ($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->input('end_date'));
            });
        }

        $pengajuanList = $query->paginate(10)->withQueryString();
        $petugasList = \App\Models\User::where('role', 'petugas')->orderBy('nama')->get();

        return view('verifikasi.index', compact('pengajuanList', 'petugasList'));
    }

    /**
     * Display the verification form for a specific pengajuan.
     */
    public function show(Pengajuan $pengajuan)
    {
        // Only pengajuan with menunggu_verifikasi can be actively verified
        // Others can still be viewed
        $pengajuan->load([
            'penerima',
            'petugas',
            'jenisBantuan',
            'survei.foto',
            'dokumen',
            'riwayatStatus.user',
        ]);

        return view('verifikasi.show', compact('pengajuan'));
    }

    /**
     * Process the verification decision.
     */
    public function verify(VerifikasiRequest $request, Pengajuan $pengajuan)
    {
        // Guard: only menunggu_verifikasi can be acted upon
        if ($pengajuan->status !== 'menunggu_verifikasi') {
            return back()->with('error', 'Pengajuan ini tidak dalam status menunggu verifikasi.');
        }

        $keputusan  = $request->input('keputusan');
        $newStatus  = $this->statusMap[$keputusan];
        $catatan    = $request->input('catatan');

        // Human-readable label for catatan default
        $labelMap = [
            'setujui' => 'Survei telah diverifikasi dan disetujui.',
            'revisi'  => 'Survei diminta untuk direvisi.',
            'tolak'   => 'Pengajuan ditolak pada tahap verifikasi admin.',
        ];

        DB::transaction(function () use ($pengajuan, $newStatus, $catatan, $keputusan, $labelMap) {
            // 1. Update status pengajuan
            $pengajuan->update(['status' => $newStatus]);

            // 2. Simpan riwayat status
            RiwayatStatus::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id'      => auth()->id(),
                'status'       => $newStatus,
                'catatan'      => $catatan ?: $labelMap[$keputusan],
            ]);
        });

        ActivityLogger::log("Verifikasi Admin: {$keputusan} untuk pengajuan {$pengajuan->kode_pengajuan}");

        $successMessages = [
            'setujui' => 'Survei berhasil disetujui. Pengajuan menunggu persetujuan pimpinan.',
            'revisi'  => 'Permintaan revisi survei berhasil dikirimkan ke petugas.',
            'tolak'   => 'Pengajuan berhasil ditolak.',
        ];

        return redirect()->route('verifikasi.index')
            ->with('success', $successMessages[$keputusan]);
    }
}
