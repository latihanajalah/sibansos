@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard Petugas Lapangan</h2>
        <p class="text-muted mb-0">Kelola pengajuan Anda, lakukan survei lapangan, dan isi kelayakan penerima.</p>
    </div>
    <div>
        <span class="badge bg-primary text-white px-3 py-2 text-capitalize">Role: {{ str_replace('_', ' ', auth()->user()->role) }}</span>
    </div>
</div>

<!-- Stats Widgets -->
<div class="row g-4 mb-5">
    <div class="col-6 col-lg-3">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-file-earmark-person fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Pengajuan Saya</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['total_pengajuan'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-clipboard fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu Survei</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['menunggu_survei'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-clipboard2-check fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Sudah Disurvei</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['sudah_survei'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Selesai</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['selesai'] }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Layout quick menus & recent lists -->
<div class="row g-4">
    <!-- Quick Access Buttons -->
    <div class="col-lg-4">
        <div class="card card-saas border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-lightning-fill text-warning me-1"></i> Aksi Cepat</h5>
            <div class="d-grid gap-3">
                <a href="#" class="btn btn-outline-primary py-3 d-flex align-items-center justify-content-start gap-3 px-3">
                    <i class="bi bi-file-earmark-person-fill fs-5"></i>
                    <div class="text-start">
                        <div class="fw-semibold">Pengajuan Saya</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Lihat list pengajuan yang Anda tangani.</div>
                    </div>
                </a>
                <a href="#" class="btn btn-outline-primary py-3 d-flex align-items-center justify-content-start gap-3 px-3">
                    <i class="bi bi-clipboard2-check-fill fs-5"></i>
                    <div class="text-start">
                        <div class="fw-semibold">Survei Lapangan</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Input data survei kelayakan rumah/ekonomi.</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Assigned Submissions -->
    <div class="col-lg-8">
        <div class="card card-saas border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-task text-primary me-1"></i> Pengajuan Saya Terkini</h5>
            
            @if($recentPengajuan->isEmpty())
                <x-empty-state title="Belum ada pengajuan" description="Daftar pengajuan bantuan sosial yang Anda daftarkan/tangani akan muncul di sini." />
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr class="text-muted small">
                                <th>Kode</th>
                                <th>Penerima</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPengajuan as $p)
                                <tr>
                                    <td class="fw-semibold text-dark">{{ $p->kode_pengajuan }}</td>
                                    <td>{{ $p->penerima->nama ?? 'N/A' }}</td>
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
