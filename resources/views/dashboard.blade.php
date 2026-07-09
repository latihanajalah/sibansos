@extends('layouts.app')

@section('content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
        color: white;
        padding: 2.5rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(220, 38, 38, 0.15);
    }

    .dashboard-header h1 {
        font-size: 2rem;
        font-weight: 800;
        margin: 0;
        margin-bottom: 0.5rem;
    }

    .dashboard-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
    }

    .stat-card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 1rem;
        padding: 1.75rem;
        transition: all 0.3s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #DC2626, #991B1B);
    }

    .stat-card:hover {
        border-color: #DC2626;
        box-shadow: var(--sb-shadow-md);
        transform: translateY(-4px);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
    }

    .stat-icon.primary { background: #FEE2E2; color: #DC2626; }
    .stat-icon.success { background: #D1FAE5; color: #10B981; }
    .stat-icon.warning { background: #FEF3C7; color: #F59E0B; }
    .stat-icon.info { background: #DBEAFE; color: #3B82F6; }

    .stat-label {
        font-size: 0.875rem;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: #111827;
        line-height: 1;
    }

    .shortcuts-section {
        background: white;
        border-radius: 1rem;
        border: 1px solid #E5E7EB;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--sb-shadow-sm);
    }

    .shortcuts-section h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .shortcuts-section h3 i {
        color: #DC2626;
        font-size: 1.5rem;
    }

    .shortcuts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .shortcut-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1.5rem;
        background: #F9FAFB;
        border: 2px solid #E5E7EB;
        border-radius: 1rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        text-align: center;
    }

    .shortcut-btn:hover {
        border-color: #DC2626;
        background: #FEE2E2;
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(220, 38, 38, 0.15);
    }

    .shortcut-icon {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #DC2626;
        border: 2px solid #FEE2E2;
    }

    .shortcut-text {
        font-size: 0.9rem;
        font-weight: 600;
        color: #111827;
    }

    .shortcut-desc {
        font-size: 0.75rem;
        color: #6B7280;
        margin-top: 0.25rem;
    }

    .info-card {
        background: white;
        border-radius: 1rem;
        border: 1px solid #E5E7EB;
        padding: 2rem;
        box-shadow: var(--sb-shadow-sm);
    }

    .info-card h4 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #E5E7EB;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #6B7280;
    }

    .info-value {
        color: #111827;
        font-weight: 600;
    }

    .badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .badge-primary {
        background: #FEE2E2;
        color: #DC2626;
    }

    .badge-success {
        background: #D1FAE5;
        color: #10B981;
    }

    .badge-warning {
        background: #FEF3C7;
        color: #F59E0B;
    }

    .badge-info {
        background: #DBEAFE;
        color: #3B82F6;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            padding: 1.5rem;
        }

        .dashboard-header h1 {
            font-size: 1.5rem;
        }

        .shortcuts-grid {
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 0.75rem;
        }

        .shortcut-btn {
            padding: 1rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }
    }
</style>

<div class="dashboard-header">
    <h1>Selamat Datang Kembali! 👋</h1>
    <p>{{ auth()->user()->nama }}, Anda login sebagai <strong>{{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}</strong></p>
</div>

{{-- STATISTICS --}}
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-label">Total Penerima</div>
            <div class="stat-value">2,341</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-file-earmark-check"></i>
            </div>
            <div class="stat-label">Pengajuan Disetujui</div>
            <div class="stat-value">856</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Pending Review</div>
            <div class="stat-value">124</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-truck"></i>
            </div>
            <div class="stat-label">Bantuan Disalurkan</div>
            <div class="stat-value">1,023</div>
        </div>
    </div>
</div>

{{-- QUICK SHORTCUTS --}}
<div class="shortcuts-section">
    <h3>
        <i class="bi bi-lightning-fill"></i>
        Akses Cepat
    </h3>
    <div class="shortcuts-grid">
        @php
        $shortcuts = [];
        $role = auth()->user()->role;

        if ($role === 'super_admin' || $role === 'admin') {
            $shortcuts = [
                ['icon' => 'bi-person-plus', 'title' => 'Tambah Pengguna', 'desc' => 'Buat akun pengguna baru', 'url' => '/admin/users/create', 'color' => '#DC2626'],
                ['icon' => 'bi-people', 'title' => 'Kelola Pengguna', 'desc' => 'Atur hak akses pengguna', 'url' => '/admin/users', 'color' => '#10B981'],
                ['icon' => 'bi-person-check', 'title' => 'Penerima', 'desc' => 'Kelola data penerima', 'url' => '/admin/penerima', 'color' => '#F59E0B'],
                ['icon' => 'bi-file-earmark', 'title' => 'Pengajuan', 'desc' => 'Review pengajuan bantuan', 'url' => '/admin/pengajuan', 'color' => '#3B82F6'],
                ['icon' => 'bi-clipboard-check', 'title' => 'Survei', 'desc' => 'Kelola data survei', 'url' => '/admin/survei', 'color' => '#8B5CF6'],
                ['icon' => 'bi-bar-chart', 'title' => 'Laporan', 'desc' => 'Lihat laporan dan statistik', 'url' => '/admin/laporan', 'color' => '#EC4899'],
            ];
        } elseif ($role === 'petugas') {
            $shortcuts = [
                ['icon' => 'bi-person-plus', 'title' => 'Tambah Pengajuan', 'desc' => 'Buat pengajuan baru', 'url' => '/petugas/pengajuan/create', 'color' => '#DC2626'],
                ['icon' => 'bi-file-earmark', 'title' => 'Pengajuan Saya', 'desc' => 'Lihat daftar pengajuan', 'url' => '/petugas/pengajuan', 'color' => '#10B981'],
                ['icon' => 'bi-search', 'title' => 'Survei', 'desc' => 'Lakukan survei lapangan', 'url' => '/petugas/survei', 'color' => '#F59E0B'],
                ['icon' => 'bi-file-text', 'title' => 'Laporan', 'desc' => 'Buat laporan aktivitas', 'url' => '/petugas/laporan', 'color' => '#3B82F6'],
            ];
        } elseif ($role === 'pimpinan') {
            $shortcuts = [
                ['icon' => 'bi-file-earmark-check', 'title' => 'Verifikasi', 'desc' => 'Review pengajuan untuk disetujui', 'url' => '/pimpinan/persetujuan', 'color' => '#DC2626'],
                ['icon' => 'bi-bar-chart', 'title' => 'Laporan', 'desc' => 'Lihat laporan dan analisis', 'url' => '/pimpinan/laporan', 'color' => '#10B981'],
                ['icon' => 'bi-graph-up', 'title' => 'Statistik', 'desc' => 'Dashboard statistik lengkap', 'url' => '/pimpinan/statistik', 'color' => '#F59E0B'],
            ];
        }
        @endphp

        @foreach($shortcuts as $shortcut)
        <a href="{{ $shortcut['url'] }}" class="shortcut-btn" title="{{ $shortcut['desc'] }}">
            <div class="shortcut-icon" style="color: {{ $shortcut['color'] }}; border-color: {{ $shortcut['color'] }}20;">
                <i class="bi {{ $shortcut['icon'] }}"></i>
            </div>
            <div class="shortcut-text">{{ $shortcut['title'] }}</div>
            <div class="shortcut-desc">{{ $shortcut['desc'] }}</div>
        </a>
        @endforeach
    </div>
</div>

{{-- USER INFO CARD --}}
<div class="row g-4">
    <div class="col-lg-6">
        <div class="info-card">
            <h4>
                <i class="bi bi-person-circle" style="color: #DC2626;"></i>
                Informasi Akun
            </h4>
            <div class="info-row">
                <span class="info-label">Nama Lengkap</span>
                <span class="info-value">{{ auth()->user()->nama }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email</span>
                <span class="info-value">{{ auth()->user()->email }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Peran/Role</span>
                <span class="badge badge-primary">{{ str_replace('_', ' ', ucfirst(auth()->user()->role)) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="badge badge-success">Aktif</span>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="info-card">
            <h4>
                <i class="bi bi-shield-check" style="color: #10B981;"></i>
                Ringkasan Sistem
            </h4>
            <div class="info-row">
                <span class="info-label">Versi Sistem</span>
                <span class="info-value">v2.0.0</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Login Terakhir</span>
                <span class="info-value">{{ \Carbon\Carbon::now()->format('d M Y H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status Koneksi</span>
                <span class="badge badge-success">Terhubung</span>
            </div>
            <div class="info-row">
                <span class="info-label">Mode</span>
                <span class="badge badge-info">Produksi</span>
            </div>
        </div>
    </div>
</div>

@endsection
