@extends('layouts.app')

@section('title', 'Survei Lapangan')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .survei-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .survei-table thead tr th {
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
    .survei-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .survei-table tbody tr:last-child td { border-bottom: none; }
    .survei-table tbody tr { transition: background .15s; }
    .survei-table tbody tr:hover { background: #f8fafc; }

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

<x-breadcrumb :items="['Survei Lapangan' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Survei Lapangan</h2>
        <p>Daftar hasil survei lapangan pengajuan bantuan sosial.</p>
    </div>
</div>

@if(auth()->user()->role === 'petugas')
    @php
        $pendingSurvei = \App\Models\Pengajuan::where('petugas_id', auth()->id())
            ->where('status', 'menunggu_survei')
            ->with('penerima')
            ->latest()
            ->get();
    @endphp
    @if($pendingSurvei->count() > 0)
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-start gap-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 text-warning"></i>
        <div class="flex-grow-1">
            <div class="fw-bold mb-2">{{ $pendingSurvei->count() }} Pengajuan Menunggu Survei</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($pendingSurvei as $p)
                    <a href="{{ route('survei.create') }}?pengajuan_id={{ $p->id }}"
                       class="btn btn-sm btn-warning fw-medium">
                        <i class="bi bi-clipboard2-check me-1"></i>
                        Isi Survei: {{ $p->kode_pengajuan }} – {{ $p->penerima->nama ?? 'N/A' }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-info-circle-fill text-info"></i>
        <span>Semua pengajuan Anda sudah disurvei. Halaman ini menampilkan riwayat survei yang sudah pernah diisi.</span>
    </div>
    @endif
@endif

{{-- Filters Card --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('survei.index') }}">
        <div class="row g-3 align-items-end">
            {{-- Search --}}
            <div class="col-md-4">
                <label class="form-label">Cari Survei</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Kode pengajuan, NIK, nama..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-3">
                <label class="form-label">Status Pengajuan</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui"           {{ request('status') === 'disetujui'           ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak"             {{ request('status') === 'ditolak'             ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Start Date --}}
            <div class="col-md-2">
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
                    @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('survei.index') }}" class="btn btn-outline-secondary">
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
<div class="survei-table-card">
    <div class="table-responsive">
        <table class="table survei-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 48px;">#</th>
                    <th>Kode Pengajuan</th>
                    <th>Nama Penerima</th>
                    <th>Petugas</th>
                    <th>Status Pengajuan</th>
                    <th>Tanggal Survei</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surveiList as $survei)
                    <tr>
                        <td class="text-muted small">{{ $surveiList->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $survei->pengajuan->kode_pengajuan ?? 'N/A' }}</span>
                        </td>
                        <td class="fw-medium text-dark">{{ $survei->pengajuan->penerima->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $survei->pengajuan->petugas->nama ?? 'N/A' }}</td>
                        <td>
                            @php $status = $survei->pengajuan->status ?? '' @endphp
                            @if($status === 'menunggu_verifikasi')
                                <span class="status-badge" style="background:#dbeafe;color:#2563eb;">
                                    <i class="bi bi-hourglass-split"></i> Menunggu Verifikasi
                                </span>
                            @elseif($status === 'disetujui')
                                <span class="status-badge" style="background:#dcfce7;color:#16a34a;">
                                    <i class="bi bi-check-circle-fill"></i> Disetujui
                                </span>
                            @elseif($status === 'ditolak')
                                <span class="status-badge" style="background:#fee2e2;color:#dc2626;">
                                    <i class="bi bi-x-circle-fill"></i> Ditolak
                                </span>
                            @else
                                <span class="status-badge text-capitalize" style="background:#f1f5f9;color:#64748b;">
                                    {{ str_replace('_', ' ', $status) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $survei->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('survei.show', $survei) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-secondary"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'petugas'
                                    && $survei->pengajuan->petugas_id === auth()->id()
                                    && $survei->pengajuan->status === 'menunggu_verifikasi')
                                    <a href="{{ route('survei.edit', $survei) }}"
                                       class="btn btn-sm btn-icon-action btn-outline-primary"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <x-empty-state
                                title="Belum ada data survei"
                                description="Survei lapangan yang sudah diisi akan tampil di sini."
                                icon="bi-clipboard2-check" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($surveiList->hasPages())
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $surveiList->firstItem() }}–{{ $surveiList->lastItem() }} dari {{ $surveiList->total() }} survei
                </p>
                {{ $surveiList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

@endsection