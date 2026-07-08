<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penerima;
use App\Models\Pengajuan;
use App\Models\JenisBantuan;
use App\Models\Penyaluran;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the corresponding dashboard based on user role.
     */
    public function index()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'super_admin' => $this->superAdminDashboard(),
            'admin'       => $this->adminDashboard(),
            'petugas'     => $this->petugasDashboard(),
            'pimpinan'    => $this->pimpinanDashboard(),
            default       => abort(403, 'Unauthorized role access.'),
        };
    }

    /**
     * Super Admin Dashboard Data
     */
    private function superAdminDashboard()
    {
        $stats = [
            'users'          => User::count(),
            'penerima'       => Penerima::count(),
            'pengajuan'      => Pengajuan::count(),
            'jenis_bantuan'  => JenisBantuan::count(),
            'penyaluran'     => Penyaluran::count(),
        ];

        $recentLogins = LogAktivitas::with('user')
            ->where('aktivitas', 'like', '%login%')
            ->orWhere('aktivitas', 'like', '%masuk%')
            ->latest()
            ->take(5)
            ->get();

        $recentPengajuan = Pengajuan::with('penerima')->latest()->take(5)->get();
        $recentPenyaluran = Penyaluran::with('pengajuan.penerima')->latest()->take(5)->get();

        return view('dashboard.super-admin', compact('stats', 'recentLogins', 'recentPengajuan', 'recentPenyaluran'));
    }

    /**
     * Admin Dashboard Data
     */
    private function adminDashboard()
    {
        $stats = [
            'penerima'         => Penerima::count(),
            'pengajuan'        => Pengajuan::count(),
            'pengajuan_pending'=> Pengajuan::where('status', 'menunggu_verifikasi')->count(),
            'pengajuan_approve'=> Pengajuan::whereIn('status', ['menunggu_persetujuan', 'siap_disalurkan', 'selesai'])->count(),
            'pengajuan_reject' => Pengajuan::whereIn('status', ['ditolak_admin', 'ditolak_pimpinan'])->count(),
        ];

        $recentPengajuan = Pengajuan::with('penerima')->latest()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'recentPengajuan'));
    }

    /**
     * Petugas Dashboard Data
     */
    private function petugasDashboard()
    {
        $userId = auth()->id();
        $stats = [
            'total_pengajuan' => Pengajuan::where('petugas_id', $userId)->count(),
            'menunggu_survei' => Pengajuan::where('petugas_id', $userId)->where('status', 'menunggu_survei')->count(),
            'sudah_survei'    => Pengajuan::where('petugas_id', $userId)->where('status', '!=', 'menunggu_survei')->count(),
            'selesai'         => Pengajuan::where('petugas_id', $userId)->where('status', 'selesai')->count(),
        ];

        $recentPengajuan = Pengajuan::where('petugas_id', $userId)
            ->with('penerima')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.petugas', compact('stats', 'recentPengajuan'));
    }

    /**
     * Pimpinan Dashboard Data
     */
    private function pimpinanDashboard()
    {
        $stats = [
            'total_pengajuan'   => Pengajuan::count(),
            'menunggu_approval' => Pengajuan::where('status', 'menunggu_persetujuan')->count(),
            'disetujui'         => Pengajuan::whereIn('status', ['siap_disalurkan', 'selesai'])->count(),
            'ditolak'           => Pengajuan::where('status', 'ditolak_pimpinan')->count(),
            'penyaluran'        => Penyaluran::count(),
        ];

        $pendingApprovals = Pengajuan::where('status', 'menunggu_persetujuan')
            ->with(['penerima', 'jenisBantuan'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.pimpinan', compact('stats', 'pendingApprovals'));
    }
}
