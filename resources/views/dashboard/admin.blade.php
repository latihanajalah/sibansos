@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard Admin</h2>
        <p class="text-muted mb-0">Kelola data bantuan, penerima manfaat, dan verifikasi berkas pengajuan.</p>
    </div>
    <div>
        <span class="badge bg-primary text-white px-3 py-2 text-capitalize">Role: {{ str_replace('_', ' ', auth()->user()->role) }}</span>
    </div>
</div>

<!-- Stats Widgets -->
<div class="row g-4 mb-5">
    <div class="col-6 col-md-4 col-lg">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-person-badge fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Penerima</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['penerima'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-file-earmark-text fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Pengajuan</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['pengajuan'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-lg">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['pengajuan_pending'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-lg">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Disetujui</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['pengajuan_approve'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-6 col-lg">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger-subtle text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-x-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Ditolak</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['pengajuan_reject'] }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Menu & Submissions layout -->
<div class="row g-4">
    <!-- Action Cards / Quick Menu -->
    <div class="col-lg-4">
        <div class="card card-saas border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-4"><i class="bi bi-lightning-fill text-warning me-1"></i> Pintasan Cepat</h5>
            <div class="d-grid gap-3">
                <a href="#" class="btn btn-outline-primary py-3 d-flex align-items-center justify-content-start gap-3 px-3">
                    <i class="bi bi-person-badge-fill fs-5"></i>
                    <div class="text-start">
                        <div class="fw-semibold">Data Penerima</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Tambah & kelola penerima bansos.</div>
                    </div>
                </a>
                <a href="#" class="btn btn-outline-primary py-3 d-flex align-items-center justify-content-start gap-3 px-3">
                    <i class="bi bi-file-earmark-text-fill fs-5"></i>
                    <div class="text-start">
                        <div class="fw-semibold">Daftar Pengajuan</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Proses dokumen pengajuan bansos.</div>
                    </div>
                </a>
                <a href="#" class="btn btn-outline-primary py-3 d-flex align-items-center justify-content-start gap-3 px-3">
                    <i class="bi bi-gift-fill fs-5"></i>
                    <div class="text-start">
                        <div class="fw-semibold">Jenis Bantuan</div>
                        <div class="small text-muted" style="font-size: 0.75rem;">Atur daftar bantuan sosial tersedia.</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Submissions -->
    <div class="col-lg-8">
        <div class="card card-saas border-0 p-4 h-100">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-file-earmark-text-fill text-primary me-1"></i> Daftar Pengajuan Terbaru</h5>
            
            @if($recentPengajuan->isEmpty())
                <x-empty-state title="Belum ada pengajuan" description="Pengajuan bansos terbaru akan muncul di sini setelah diinput." />
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
                                        @elseif($p->status === 'menunggu')
                                            <span class="badge bg-warning-subtle text-warning">Menunggu</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary text-capitalize">{{ $p->status }}</span>
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
