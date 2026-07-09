@extends('layouts.app')

@section('title', 'Laporan Penyaluran')

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

<x-breadcrumb :items="['Laporan & Statistik' => route('laporan.index'), 'Laporan Penyaluran' => '#']" />

<div class="page-header">
    <div class="page-header-content">
        <h2>Laporan Penyaluran</h2>
        <p>Daftar seluruh penyaluran bantuan sosial yang telah dilakukan.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('laporan.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('laporan.export.pdf', 'penyaluran') . '?' . http_build_query(request()->all()) }}"
           class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
        </a>
        @if(auth()->user()->role !== 'pimpinan')
        <a href="{{ route('laporan.export.excel', 'penyaluran') . '?' . http_build_query(request()->all()) }}"
           class="btn btn-success">
            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
        </a>
        @endif
    </div>
</div>

{{-- Filter --}}
@include('laporan.partials.filter', ['type' => 'penyaluran'])

{{-- Table Card --}}
<div class="table-card">
    <div class="px-4 py-3 border-bottom d-flex align-items-center justify-content-between gap-3">
        <h5 class="mb-0"><i class="bi bi-truck text-success me-2"></i>Data Penyaluran</h5>
        <span class="badge bg-success">{{ $penyaluranList->count() }} data</span>
    </div>

    @if($penyaluranList->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size:2.5rem;"></i>
        <p class="text-muted mt-2 mb-0">Tidak ada data penyaluran sesuai filter yang dipilih.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="text-muted small">
                    <th style="width: 48px;">#</th>
                    <th>Kode Pengajuan</th>
                    <th>Nama Penerima</th>
                    <th>Jenis Bantuan</th>
                    <th>Petugas</th>
                    <th>Tanggal Penyaluran</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penyaluranList as $i => $row)
                <tr>
                    <td class="text-muted small">{{ $i + 1 }}</td>
                    <td>
                        <span class="fw-bold" style="font-family:monospace;font-size:.85rem;">
                            {{ $row->pengajuan->kode_pengajuan ?? '-' }}
                        </span>
                    </td>
                    <td class="fw-medium text-dark">{{ $row->pengajuan->penerima->nama ?? '-' }}</td>
                    <td>
                        @foreach($row->pengajuan->jenisBantuan ?? [] as $jb)
                        <span class="badge-pill me-1" style="background:#dcfce7;color:#16a34a;">
                            <i class="bi bi-check2-circle"></i>{{ $jb->nama_bantuan }}
                        </span>
                        @endforeach
                    </td>
                    <td class="text-muted small">{{ $row->petugas->name ?? '-' }}</td>
                    <td class="text-muted small fw-medium">
                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                    </td>
                    <td>
                        <span class="badge-pill" style="background:#dcfce7;color:#16a34a;">
                            <i class="bi bi-check-circle-fill"></i>Selesai
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection