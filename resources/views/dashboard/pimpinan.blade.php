@extends('layouts.app')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
    <div>
        <h2 class="fw-bold text-dark mb-1">Dashboard Pimpinan</h2>
        <p class="text-muted mb-0">Pantau statistik penyaluran bantuan sosial dan berikan keputusan persetujuan.</p>
    </div>
    <div>
        <span class="badge bg-primary text-white px-3 py-2 text-capitalize">Role: {{ str_replace('_', ' ', auth()->user()->role) }}</span>
    </div>
</div>

<!-- Stats Widgets -->
<div class="row g-4 mb-5">
    <div class="col-6 col-lg-2.4 col-md-4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary-light text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-file-earmark-text fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Total Pengajuan</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['total_pengajuan'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2.4 col-md-4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-warning-subtle text-warning rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-hourglass-split fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Menunggu</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['menunggu_approval'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2.4 col-md-4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-success-subtle text-success rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-check-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Disetujui</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['disetujui'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-2.4 col-md-6">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger-subtle text-danger rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-x-circle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Ditolak</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['ditolak'] }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-2.4 col-md-6">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info-subtle text-info rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                    <i class="bi bi-truck fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">Penyaluran</div>
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['penyaluran'] }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approval List -->
<div class="card card-saas border-0 p-4 mb-4">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clipboard2-check text-primary me-1"></i> Pengajuan Menunggu Persetujuan</h5>
    
    @if($pendingApprovals->isEmpty())
        <x-empty-state title="Tidak ada pengajuan pending" description="Saat ini belum ada pengajuan baru yang menunggu persetujuan Anda." />
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-muted small">
                        <th>Kode</th>
                        <th>Nama Penerima</th>
                        <th>Bantuan Diajukan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingApprovals as $p)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $p->kode_pengajuan }}</td>
                            <td>
                                <div class="fw-medium text-dark">{{ $p->penerima->nama ?? 'N/A' }}</div>
                                <span class="text-muted small" style="font-size: 0.75rem;">NIK: {{ $p->penerima->nik ?? 'N/A' }}</span>
                            </td>
                            <td>
                                @foreach($p->jenisBantuan as $jb)
                                    <span class="badge bg-primary-light text-primary me-1">{{ $jb->nama_bantuan }}</span>
                                @endforeach
                            </td>
                            <td class="text-muted small">{{ $p->tanggal_pengajuan->format('d M Y') }}</td>
                            <td>
                                <a href="#" class="btn btn-outline-primary btn-sm px-3 py-1">Tinjau Kelayakan</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
