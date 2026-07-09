@extends('layouts.app')

@section('title', 'Master Data Penerima')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .penerima-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .penerima-table thead tr th {
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
    .penerima-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .penerima-table tbody tr:last-child td { border-bottom: none; }
    .penerima-table tbody tr { transition: background .15s; }
    .penerima-table tbody tr:hover { background: #f8fafc; }

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

<x-breadcrumb :items="['Master Data Penerima' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Master Data Penerima</h2>
        <p>Kelola informasi penerima manfaat bantuan sosial.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('penerima.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah Penerima
        </a>
    </div>
</div>

{{-- ─── Stat Cards ─── --}}
@php
    $genderCounts = [
        'laki_laki' => $penerimaList->getCollection()->where('jenis_kelamin', 'Laki-laki')->count(),
        'perempuan' => $penerimaList->getCollection()->where('jenis_kelamin', 'Perempuan')->count(),
    ];

    $penerimaStatCards = [
        ['label' => 'Total Penerima', 'value' => $penerimaList->total(),     'icon' => 'bi-person-badge-fill', 'bg' => '#dbeafe', 'ic' => '#2563eb'],
        ['label' => 'Laki-laki',      'value' => $genderCounts['laki_laki'], 'icon' => 'bi-gender-male',       'bg' => '#dbeafe', 'ic' => '#2563eb'],
        ['label' => 'Perempuan',      'value' => $genderCounts['perempuan'], 'icon' => 'bi-gender-female',     'bg' => '#fee2e2', 'ic' => '#dc2626'],
    ];
@endphp

<div class="row g-4 mb-5">
    @foreach($penerimaStatCards as $card)
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
    <form method="GET" action="{{ route('penerima.index') }}">
        <div class="row g-3 align-items-end">
            {{-- Search Input --}}
            <div class="col-md-8">
                <label class="form-label">Cari Penerima</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0 ps-0"
                           placeholder="Cari NIK, KK, atau nama..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Actions --}}
            <div class="col-md-4">
                <div class="filter-actions">
                    @if(request('search'))
                        <a href="{{ route('penerima.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg me-1"></i> Reset
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
<div class="penerima-table-card">
    <div class="table-responsive">
        <table class="table penerima-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 48px;">#</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>No KK</th>
                    <th>Jenis Kelamin</th>
                    <th>Desa</th>
                    <th>Kecamatan</th>
                    <th>Kabupaten</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penerimaList as $penerima)
                    <tr>
                        <td class="text-muted small">{{ $penerimaList->firstItem() + $loop->index }}</td>
                        <td><span class="fw-semibold text-dark">{{ $penerima->nik }}</span></td>
                        <td class="fw-medium text-dark">{{ $penerima->nama }}</td>
                        <td class="text-muted small">{{ $penerima->no_kk }}</td>
                        <td>
                            @if($penerima->jenis_kelamin === 'Laki-laki')
                                <span class="status-badge" style="background:#dbeafe;color:#2563eb;">
                                    <i class="bi bi-gender-male"></i> Laki-laki
                                </span>
                            @else
                                <span class="status-badge" style="background:#fee2e2;color:#dc2626;">
                                    <i class="bi bi-gender-female"></i> Perempuan
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $penerima->desa }}</td>
                        <td class="text-muted small">{{ $penerima->kecamatan }}</td>
                        <td class="text-muted small">{{ $penerima->kabupaten }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('penerima.show', $penerima) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-secondary"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('penerima.edit', $penerima) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-primary"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('penerima.destroy', $penerima) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-icon-action btn-outline-danger btn-delete"
                                            data-name="{{ $penerima->nama }}"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-5">
                            <x-empty-state
                                title="Belum ada data penerima"
                                description="Klik tombol 'Tambah Penerima' untuk menambahkan data penerima bansos baru."
                                icon="bi-person-badge" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($penerimaList->hasPages())
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $penerimaList->firstItem() }}–{{ $penerimaList->lastItem() }} dari {{ $penerimaList->total() }} penerima
                </p>
                {{ $penerimaList->links('pagination::bootstrap-5') }}
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
            title: 'Hapus Data Penerima?',
            html: `Data <strong>${name}</strong> akan dihapus secara permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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