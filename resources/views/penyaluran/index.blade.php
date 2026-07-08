@extends('layouts.app')

@section('content')

<x-breadcrumb :items="['Penyaluran Bantuan' => '#']" />

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Penyaluran Bantuan</h2>
        <p class="text-muted mb-0">Catatan penyaluran bantuan sosial kepada penerima manfaat.</p>
    </div>
</div>

{{-- Filters Card --}}
<div class="card card-saas border-0 p-4 mb-4">
    <form method="GET" action="{{ route('penyaluran.index') }}">
        <div class="row g-3">
            {{-- Search --}}
            <div class="col-md-5">
                <label class="form-label small fw-medium text-muted">Cari Penyaluran</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Nama penerima, NIK, kode pengajuan..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Start Date --}}
            <div class="col-md-3">
                <label class="form-label small fw-medium text-muted">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            {{-- End Date --}}
            <div class="col-md-3">
                <label class="form-label small fw-medium text-muted">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            {{-- Actions --}}
            <div class="col-12 d-flex gap-2 justify-content-end mt-1">
                @if(request()->anyFilled(['search', 'start_date', 'end_date']))
                    <a href="{{ route('penyaluran.index') }}" class="btn btn-outline-secondary px-4">Reset Filter</a>
                @endif
                <button type="submit" class="btn btn-primary px-4">Terapkan Filter</button>
            </div>
        </div>
    </form>
</div>

{{-- Table Card --}}
<div class="card card-saas border-0 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-muted small" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em;">
                    <th style="width: 48px;">#</th>
                    <th>Kode Pengajuan</th>
                    <th>Nama Penerima</th>
                    <th>Petugas</th>
                    <th>Tanggal Penyaluran</th>
                    <th>Status</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penyaluranList as $peny)
                    <tr>
                        <td class="text-muted small">{{ $penyaluranList->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $peny->pengajuan->kode_pengajuan ?? 'N/A' }}</span>
                        </td>
                        <td class="fw-medium text-dark">{{ $peny->pengajuan->penerima->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $peny->petugas->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $peny->tanggal->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-success-subtle text-success text-capitalize">{{ $peny->status }}</span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('penyaluran.show', $peny) }}"
                                   class="btn btn-sm btn-outline-secondary px-3 py-1 d-flex align-items-center gap-1" title="Detail">
                                    <i class="bi bi-eye"></i> Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <x-empty-state
                                title="Belum ada data penyaluran"
                                description="Penyaluran bantuan sosial yang dicatat akan muncul di sini."
                                icon="bi-truck" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($penyaluranList->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <p class="text-muted small mb-0">
                Menampilkan {{ $penyaluranList->firstItem() }}–{{ $penyaluranList->lastItem() }} dari {{ $penyaluranList->total() }} data penyaluran
            </p>
            {{ $penyaluranList->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
