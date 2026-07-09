@extends('layouts.app')

@section('title', 'Verifikasi Admin')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .verifikasi-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .verifikasi-table thead tr th {
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
    .verifikasi-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .verifikasi-table tbody tr:last-child td { border-bottom: none; }
    .verifikasi-table tbody tr { transition: background .15s; }
    .verifikasi-table tbody tr:hover { background: #f8fafc; }

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

<x-breadcrumb :items="['Verifikasi Admin' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Verifikasi Pengajuan</h2>
        <p>Verifikasi hasil survei lapangan untuk menentukan status pengajuan bantuan sosial.</p>
    </div>
</div>

{{-- Filters Card --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('verifikasi.index') }}">
        <div class="row g-3 align-items-end">
            {{-- Search --}}
            <div class="col-md-3">
                <label class="form-label">Cari Pengajuan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Nama, NIK, kode..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="revisi_survei"       {{ request('status') === 'revisi_survei' ? 'selected' : '' }}>Revisi Survei</option>
                    <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="ditolak_admin"        {{ request('status') === 'ditolak_admin' ? 'selected' : '' }}>Ditolak Admin</option>
                </select>
            </div>

            {{-- Petugas Filter --}}
            <div class="col-md-2">
                <label class="form-label">Petugas</label>
                <select name="petugas_id" class="form-select">
                    <option value="">-- Semua Petugas --</option>
                    @foreach($petugasList as $p)
                        <option value="{{ $p->id }}" {{ request('petugas_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Start Date --}}
            <div class="col-md-2">
                <label class="form-label">Mulai Survei</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            {{-- End Date --}}
            <div class="col-md-2">
                <label class="form-label">Akhir Survei</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            {{-- Actions --}}
            <div class="col-12">
                <div class="filter-actions">
                    @if(request()->anyFilled(['search', 'status', 'petugas_id', 'start_date', 'end_date']))
                        <a href="{{ route('verifikasi.index') }}" class="btn btn-outline-secondary">
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
<div class="verifikasi-table-card">
    <div class="table-responsive">
        <table class="table verifikasi-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 48px;">#</th>
                    <th>Kode Pengajuan</th>
                    <th>Nama Penerima</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th>Tanggal Survei</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanList as $pengajuan)
                    <tr>
                        <td class="text-muted small">{{ $pengajuanList->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $pengajuan->kode_pengajuan }}</span>
                        </td>
                        <td class="fw-medium text-dark">{{ $pengajuan->penerima->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $pengajuan->petugas->nama ?? 'N/A' }}</td>
                        <td>
                            @if($pengajuan->status === 'menunggu_verifikasi')
                                <span class="status-badge" style="background:#fef3c7;color:#d97706;">
                                    <i class="bi bi-hourglass-split"></i> Menunggu Verifikasi
                                </span>
                            @elseif($pengajuan->status === 'revisi_survei')
                                <span class="status-badge" style="background:#dbeafe;color:#2563eb;">
                                    <i class="bi bi-arrow-repeat"></i> Revisi Survei
                                </span>
                            @elseif($pengajuan->status === 'menunggu_persetujuan')
                                <span class="status-badge" style="background:#ede9fe;color:#7c3aed;">
                                    <i class="bi bi-hourglass-split"></i> Menunggu Persetujuan
                                </span>
                            @elseif($pengajuan->status === 'ditolak_admin')
                                <span class="status-badge" style="background:#fee2e2;color:#dc2626;">
                                    <i class="bi bi-x-circle-fill"></i> Ditolak Admin
                                </span>
                            @else
                                <span class="status-badge text-capitalize" style="background:#f1f5f9;color:#64748b;">
                                    {{ str_replace('_', ' ', $pengajuan->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $pengajuan->survei ? $pengajuan->survei->created_at->format('d M Y') : '-' }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                @if($pengajuan->status === 'menunggu_verifikasi')
                                    <a href="{{ route('verifikasi.show', $pengajuan) }}"
                                       class="btn btn-sm btn-primary px-3 py-1 d-flex align-items-center gap-1" title="Verifikasi">
                                        <i class="bi bi-shield-check"></i> Verifikasi
                                    </a>
                                @else
                                    <a href="{{ route('verifikasi.show', $pengajuan) }}"
                                       class="btn btn-sm btn-icon-action btn-outline-secondary"
                                       title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <x-empty-state
                                title="Belum ada pengajuan untuk diverifikasi"
                                description="Pengajuan yang selesai disurvei oleh petugas lapangan akan muncul di sini."
                                icon="bi-shield-check" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pengajuanList->hasPages())
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $pengajuanList->firstItem() }}–{{ $pengajuanList->lastItem() }} dari {{ $pengajuanList->total() }} pengajuan
                </p>
                {{ $pengajuanList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

@endsection