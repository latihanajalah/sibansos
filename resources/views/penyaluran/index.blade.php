@extends('layouts.app')

@section('title', 'Penyaluran Bantuan')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .penyaluran-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .penyaluran-table thead tr th {
        background: #f8fafc;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        font-weight: 700;
        color: #64748b;
        padding: .9rem 1.25rem;
        border-bottom: 1.5px solid #eef1f5;
        white-space: nowrap;
    }
    .penyaluran-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .penyaluran-table tbody tr:last-child td { border-bottom: none; }
    .penyaluran-table tbody tr { transition: background .15s; }
    .penyaluran-table tbody tr:hover { background: #f8fafc; }

    .status-badge {
        font-size: .72rem;
        font-weight: 600;
        padding: .32rem .7rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        white-space: nowrap;
    }

    .btn-icon-action {
        width: 34px; height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        padding: 0;
    }
</style>
@endpush

@section('content')

<x-breadcrumb :items="['Penyaluran Bantuan' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Penyaluran Bantuan</h2>
        <p>Catatan penyaluran bantuan sosial kepada penerima manfaat.</p>
    </div>
</div>

{{-- Filters Card --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('penyaluran.index') }}">
        <div class="row g-3 align-items-end">
            {{-- Search --}}
            <div class="col-md-6">
                <label class="form-label">Cari Penyaluran</label>
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
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            {{-- End Date --}}
            <div class="col-md-3">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            {{-- Actions --}}
            <div class="col-12">
                <div class="filter-actions">
                    @if(request()->anyFilled(['search', 'start_date', 'end_date']))
                        <a href="{{ route('penyaluran.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i> Reset
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Terapkan Filter
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Table Card --}}
<div class="penyaluran-table-card">
    <div class="table-responsive">
        <table class="table penyaluran-table align-middle mb-0">
            <thead>
                <tr>
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
                            <span class="status-badge text-capitalize" style="background:#dcfce7;color:#16a34a;">
                                <i class="bi bi-check-circle-fill"></i> {{ $peny->status }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="{{ route('penyaluran.show', $peny) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-secondary"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
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
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $penyaluranList->firstItem() }}–{{ $penyaluranList->lastItem() }} dari {{ $penyaluranList->total() }} data penyaluran
                </p>
                {{ $penyaluranList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

@endsection