<?php

namespace App\Http\Controllers;

use App\Helpers\ActivityLogger;
use App\Http\Requests\PersetujuanRequest;
use App\Models\Pengajuan;
use App\Models\RiwayatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersetujuanController extends Controller
{
    /**
     * Map decision parameter to new pengajuan status.
     */
    private array $statusMap = [
        'setujui' => 'siap_disalurkan',
        'tolak'   => 'ditolak_pimpinan',
    ];

    /**
     * Display a listing of pengajuan waiting for or processed by Pimpinan.
     */
    public function index(Request $request)
    {
        $role = auth()->user()->role;
        
        // Pimpinan sees waiting list and their decisions, Super Admin sees all
        $query = Pengajuan::with(['penerima', 'petugas', 'survei', 'riwayatStatus'])
            ->whereIn('status', ['menunggu_persetujuan', 'siap_disalurkan', 'ditolak_pimpinan'])
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

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Date Filter (verifikasi date / riwayat_status of menunggu_persetujuan)
        if ($request->filled('start_date')) {
            $query->whereDate('updated_at', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('updated_at', '<=', $request->input('end_date'));
        }

        $pengajuanList = $query->paginate(10)->withQueryString();

        return view('persetujuan.index', compact('pengajuanList'));
    }

    /**
     * Display the specific pengajuan details for approval.
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load([
            'penerima',
            'petugas',
            'jenisBantuan',
            'survei.foto',
            'dokumen',
            'riwayatStatus.user',
        ]);

        return view('persetujuan.show', compact('pengajuan'));
    }

    /**
     * Approve or reject the pengajuan.
     */
    public function approve(PersetujuanRequest $request, Pengajuan $pengajuan)
    {
        // Guard: only menunggu_persetujuan can be processed
        if ($pengajuan->status !== 'menunggu_persetujuan') {
            return back()->with('error', 'Pengajuan ini tidak sedang menunggu persetujuan pimpinan.');
        }

        $keputusan = $request->input('keputusan');
        $newStatus = $this->statusMap[$keputusan];
        $catatan   = $request->input('catatan');

        $defaultCatatan = [
            'setujui' => 'Pengajuan disetujui oleh pimpinan.',
            'tolak'   => 'Pengajuan ditolak oleh pimpinan.',
        ];

        DB::transaction(function () use ($pengajuan, $newStatus, $catatan, $keputusan, $defaultCatatan) {
            // 1. Update status
            $pengajuan->update(['status' => $newStatus]);

            // 2. Create status history
            RiwayatStatus::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id'      => auth()->id(),
                'status'       => $newStatus,
                'catatan'      => $catatan ?: $defaultCatatan[$keputusan],
            ]);
        });

        ActivityLogger::log("Persetujuan Pimpinan: {$keputusan} untuk pengajuan {$pengajuan->kode_pengajuan}");

        $successMessages = [
            'setujui' => 'Pengajuan berhasil disetujui and siap disalurkan.',
            'tolak'   => 'Pengajuan berhasil ditolak.',
        ];

        return redirect()->route('persetujuan.index')
            ->with('success', $successMessages[$keputusan]);
    }
}
