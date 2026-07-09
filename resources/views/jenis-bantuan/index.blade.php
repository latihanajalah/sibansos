@extends('layouts.app')

@section('title', 'Master Jenis Bantuan')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .jenis-bantuan-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .jenis-bantuan-table thead tr th {
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
    .jenis-bantuan-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .jenis-bantuan-table tbody tr:last-child td { border-bottom: none; }
    .jenis-bantuan-table tbody tr { transition: background .15s; }
    .jenis-bantuan-table tbody tr:hover { background: #f8fafc; }

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

<x-breadcrumb :items="['Jenis Bantuan' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Master Jenis Bantuan</h2>
        <p>Kelola daftar bantuan sosial yang terintegrasi di sistem.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('jenis-bantuan.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Jenis Bantuan
        </a>
    </div>
</div>

{{-- ─── Stat Cards ─── --}}
@php
    $statusCounts = [
        'aktif'    => $jenisBantuanList->getCollection()->where('status', true)->count(),
        'nonaktif' => $jenisBantuanList->getCollection()->where('status', false)->count(),
    ];

    $jbStatCards = [
        ['label' => 'Total Jenis Bantuan', 'value' => $jenisBantuanList->total(), 'icon' => 'bi-gift-fill',         'bg' => '#dbeafe', 'ic' => '#2563eb'],
        ['label' => 'Aktif',               'value' => $statusCounts['aktif'],     'icon' => 'bi-check-circle-fill', 'bg' => '#dcfce7', 'ic' => '#16a34a'],
        ['label' => 'Nonaktif',            'value' => $statusCounts['nonaktif'],  'icon' => 'bi-x-circle-fill',     'bg' => '#fee2e2', 'ic' => '#dc2626'],
    ];
@endphp

<div class="row g-4 mb-5">
    @foreach($jbStatCards as $card)
    <div class="col-6 col-md-4">
        <div class="card card-saas p-3 border-0">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background:{{ $card['bg'] }}; color:{{ $card['ic'] }};">
                    <i class="bi {{ $card['icon'] }} fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small">{{ $card['label'] }}</div>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($card['value']) }}</h3>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter Card --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('jenis-bantuan.index') }}">
        <div class="row g-3">
            {{-- Search Input --}}
            <div class="col-md-6">
                <label class="form-label">Cari Jenis Bantuan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0 ps-0"
                           placeholder="Cari kode atau nama bantuan..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Actions --}}
            <div class="col-12">
                <div class="filter-actions">
                    @if(request('search'))
                        <a href="{{ route('jenis-bantuan.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i> Reset Filter
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Table Card --}}
<div class="jenis-bantuan-table-card">
    <div class="table-responsive">
        <table class="table jenis-bantuan-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 48px;">#</th>
                    <th style="width: 120px;">Kode</th>
                    <th>Nama Bantuan</th>
                    <th>Deskripsi</th>
                    <th style="width: 120px;">Status</th>
                    <th style="width: 150px;">Dibuat</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jenisBantuanList as $item)
                    <tr>
                        <td class="text-muted small">{{ $jenisBantuanList->firstItem() + $loop->index }}</td>
                        <td><span class="fw-bold text-dark">{{ $item->kode }}</span></td>
                        <td class="fw-medium text-dark">{{ $item->nama_bantuan }}</td>
                        <td class="text-muted small text-truncate" style="max-width: 250px;">
                            {{ $item->deskripsi ?? '-' }}
                        </td>
                        <td>
                            @if($item->status)
                                <span class="status-badge" style="background:#dcfce7;color:#16a34a;">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            @else
                                <span class="status-badge" style="background:#fee2e2;color:#dc2626;">
                                    <i class="bi bi-x-circle-fill"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('jenis-bantuan.show', $item) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-secondary"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('jenis-bantuan.edit', $item) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-primary"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(auth()->user()->role === 'super_admin')
                                    <form action="{{ route('jenis-bantuan.destroy', $item) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-icon-action btn-outline-danger btn-delete"
                                                data-name="{{ $item->nama_bantuan }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <x-empty-state
                                title="Belum ada jenis bantuan"
                                description="Klik tombol 'Tambah Jenis Bantuan' untuk menambahkan jenis bantuan sosial baru."
                                icon="bi-gift" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($jenisBantuanList->hasPages())
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $jenisBantuanList->firstItem() }}–{{ $jenisBantuanList->lastItem() }} dari {{ $jenisBantuanList->total() }} jenis bantuan
                </p>
                {{ $jenisBantuanList->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

@endsection

@push('js')
{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const name = this.getAttribute('data-name');
        const form = this.closest('form');

        Swal.fire({
            title: 'Hapus Jenis Bantuan?',
            html: `Jenis bantuan <strong>${name}</strong> akan dihapus secara permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush