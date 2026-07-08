@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard Super Admin</h2>
        <p class="text-muted mb-0">Kelola keseluruhan data sistem dan audit log aktivitas.</p>
    </div>
    <div>
        <span class="badge bg-primary text-white px-3 py-2 text-capitalize">Role: {{ str_replace('_', ' ', auth()->user()->role) }}</span>
    </div>
</div>

<!-- Stats Widgets -->
<div class="row g-4 mb-5">
    <div class="col-6 col-lg-3 col-xl-2.4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-people fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total User</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['users'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-xl-2.4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-person-badge fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Penerima</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['penerima'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-xl-2.4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-file-earmark-text fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Pengajuan</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['pengajuan'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3 col-xl-2.4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-gift fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Jenis Bantuan</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['jenis_bantuan'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-3 col-xl-2.4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-truck fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Penyaluran</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['penyaluran'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Menu -->
<div class="card card-saas border-0 mb-5 p-4">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-lightning-fill text-warning me-1"></i> Menu Pintasan Cepat</h5>
    <div class="d-flex flex-wrap gap-2">
        <a href="#" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 py-2 px-3"><i class="bi bi-people-fill"></i> Kelola User</a>
        <a href="#" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 py-2 px-3"><i class="bi bi-person-badge-fill"></i> Kelola Penerima</a>
        <a href="#" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 py-2 px-3"><i class="bi bi-file-earmark-text-fill"></i> Kelola Pengajuan</a>
        <a href="#" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 py-2 px-3"><i class="bi bi-gift-fill"></i> Kelola Bantuan</a>
        <a href="#" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2 py-2 px-3"><i class="bi bi-file-earmark-bar-graph-fill"></i> Laporan</a>
    </div>
</div>

<!-- Recent Activities Layout -->
<div class="row g-4">
    <!-- Logins -->
    <div class="col-lg-6">
        <div class="card card-saas border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history me-1 text-primary"></i> Aktivitas Login Terakhir</h5>
            @if($recentLogins->isEmpty())
                <x-empty-state title="Belum ada log masuk" description="Log login user akan ditampilkan di sini." />
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Pengguna</th>
                                <th>IP Address</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentLogins as $login)
                                <tr>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $login->user->nama ?? 'Unknown' }}</div>
                                        <span class="text-muted small" style="font-size: 0.8rem;">{{ $login->user->email ?? '' }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $login->ip_address }}</td>
                                    <td class="text-muted small">{{ $login->created_at->diffForHumans() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Submissions -->
    <div class="col-lg-6">
        <div class="card card-saas border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-text-fill me-1 text-success"></i> Pengajuan Terbaru</h5>
            @if($recentPengajuan->isEmpty())
                <x-empty-state title="Belum ada pengajuan" description="Pengajuan bansos terbaru akan muncul di sini." />
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Penerima</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPengajuan as $p)
                                <tr>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $p->penerima->nama ?? 'N/A' }}</div>
                                        <span class="text-muted small" style="font-size: 0.8rem;">{{ $p->kode_pengajuan }}</span>
                                    </td>
                                    <td class="text-muted small">{{ $p->tanggal_pengajuan->format('d M Y') }}</td>
                                    <td>
                                        @if($p->status === 'disetujui')
                                            <span class="badge bg-success-subtle text-success">Disetujui</span>
                                        @elseif($p->status === 'ditolak')
                                            <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning text-capitalize">{{ $p->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
