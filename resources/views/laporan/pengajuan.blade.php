@extends('layouts.app')

@section('title', 'Laporan Pengajuan')

@push('css')
<style>
    /* ─── Table Card (disamakan dengan Manajemen User) ─── */
    .table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .table-card table thead tr th {
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
    .table-card table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-card table tbody tr:last-child td { border-bottom: none; }
    .table-card table tbody tr { transition: background .15s; }
    .table-card table tbody tr:hover { background: #f8fafc; }

    .badge-pill {
        font-size: .72rem;
        font-weight: 600;
        padding: .32rem .7rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')

<x-breadcrumb :items="['Laporan & Statistik' => route('laporan.index'), 'Laporan Pengajuan' => '#']" />

<div class="page-header">
    <div class="page-header-content">
        <h2>Laporan Pengajuan</h2>
        <p>Daftar seluruh pengajuan bantuan sosial.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        @can('export-pdf')
        <a href="{{ route('laporan.export.pdf', 'pengajuan') . '?' . http_build_query(request()->all()) }}"
           class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
        </a>
        @endcan
        @if(auth()->user()->role !== 'pimpinan')
        <a href="{{ route('laporan.export.excel', 'pengajuan') . '?' . http_build_query(request()->all()) }}"
           class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
        </a>
        @endif
    </div>
</div>

{{-- Filter --}}
@include('laporan.partials.filter', ['type' => 'pengajuan'])

{{-- Table Card --}}
<div class="table-card">
    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3">
        <h5 class="mb-0"><i class="bi bi-file-earmark-text-fill text-primary me-2"></i>Data Pengajuan</h5>
        <span class="badge bg-primary">{{ $pengajuanList->count() }} data</span>
    </div>

    @if($pengajuanList->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size:2.5rem;"></i>
        <p class="text-muted mt-2 mb-0">Tidak ada data pengajuan sesuai filter yang dipilih.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted small">
                    <th style="width: 48px;">#</th>
                    <th>Kode Pengajuan</th>
                    <th>Nama Penerima</th>
                    <th>NIK</th>
                    <th>Jenis Bantuan</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $statusColors = [
                        'success'   => ['bg' => '#dcfce7', 'fg' => '#16a34a'],
                        'danger'    => ['bg' => '#fee2e2', 'fg' => '#dc2626'],
                        'warning'   => ['bg' => '#fef3c7', 'fg' => '#d97706'],
                        'info'      => ['bg' => '#dbeafe', 'fg' => '#0891b2'],
                        'primary'   => ['bg' => '#dbeafe', 'fg' => '#2563eb'],
                        'secondary' => ['bg' => '#f1f5f9', 'fg' => '#64748b'],
                    ];
                @endphp
                @foreach($pengajuanList as $i => $row)
                <tr>
                    <td class="text-muted small">{{ $i + 1 }}</td>
                    <td>
                        <span class="fw-bold" style="font-family:monospace;font-size:.85rem;">
                            {{ $row->kode_pengajuan }}
                        </span>
                    </td>
                    <td class="fw-medium text-dark">{{ $row->penerima->nama ?? '-' }}</td>
                    <td class="text-muted small" style="font-family:monospace;font-size:.8rem;">{{ $row->penerima->nik ?? '-' }}</td>
                    <td>
                        @foreach($row->jenisBantuan as $jb)
                        <span class="badge-pill me-1" style="background:#dbeafe;color:#2563eb;">
                            <i class="bi bi-tag-fill"></i>{{ $jb->nama_bantuan }}
                        </span>
                        @endforeach
                    </td>
                    <td class="text-muted small">{{ $row->petugas->name ?? '-' }}</td>
                    <td>
                        @php
                            $si = \App\Helpers\StatusHelper::label($row->status);
                            $sc = $statusColors[$si[1]] ?? ['bg' => '#f1f5f9', 'fg' => '#64748b'];
                        @endphp
                        <span class="badge-pill" style="background:{{ $sc['bg'] }};color:{{ $sc['fg'] }};">
                            <i class="bi {{ $si[2] }}"></i>{{ $si[0] }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $row->tanggal_pengajuan->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection