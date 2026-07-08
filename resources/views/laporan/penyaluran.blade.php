@extends('layouts.app')

@section('title', 'Laporan Penyaluran')

@push('styles')
<style>
    .filter-card { background:#fff; border:1.5px solid var(--border-color,#e2e8f0); border-radius:16px; overflow:hidden; }
    .filter-header { padding:1rem 1.5rem; background:#f8fafc; border-bottom:1.5px solid #e2e8f0; display:flex; align-items:center; gap:.6rem; font-size:.9rem; }
    .filter-body { padding:1.25rem 1.5rem; }
    .table-card { background:#fff; border:1.5px solid var(--border-color,#e2e8f0); border-radius:16px; overflow:hidden; }
    .table-card-header { padding:1rem 1.5rem; border-bottom:1.5px solid #e2e8f0; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; }
    .table-card-header h5 { margin:0; font-weight:700; font-size:.95rem; }
    .table th { font-size:.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:#64748b; background:#f8fafc; border-bottom:1.5px solid #e2e8f0; padding:.75rem 1rem; }
    .table td { font-size:.875rem; padding:.7rem 1rem; vertical-align:middle; border-bottom:1px solid #f1f5f9; }
    .table tbody tr:last-child td { border-bottom:none; }
    .table tbody tr:hover { background:#f8fafc; }
</style>
@endpush

@section('content')

<x-breadcrumb :items="['Laporan & Statistik' => route('laporan.index'), 'Laporan Penyaluran' => '#']" />

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
    <div>
        <h4 class="fw-800 mb-0" style="font-weight:800;">Laporan Penyaluran</h4>
        <p class="text-muted mb-0 small">Daftar seluruh penyaluran bantuan sosial yang telah dilakukan.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('laporan.index') }}" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;">
            <i class="bi bi-arrow-left me-1"></i>Kembali
        </a>
        <a href="{{ route('laporan.export.pdf', 'penyaluran') . '?' . http_build_query(request()->all()) }}"
           class="btn btn-sm btn-danger" style="border-radius:8px;" target="_blank">
            <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
        </a>
        @if(auth()->user()->role !== 'pimpinan')
        <a href="{{ route('laporan.export.excel', 'penyaluran') . '?' . http_build_query(request()->all()) }}"
           class="btn btn-sm btn-success" style="border-radius:8px;">
            <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
        </a>
        @endif
    </div>
</div>

{{-- Filter --}}
@include('laporan.partials.filter', ['type' => 'penyaluran'])

{{-- Tabel --}}
<div class="table-card">
    <div class="table-card-header">
        <h5><i class="bi bi-truck text-success me-2"></i>Data Penyaluran</h5>
        <span class="badge bg-success rounded-pill">{{ $penyaluranList->count() }} data</span>
    </div>

    @if($penyaluranList->isEmpty())
    <div class="text-center py-5">
        <i class="bi bi-inbox text-muted" style="font-size:2.5rem;"></i>
        <p class="text-muted mt-2 mb-0">Tidak ada data penyaluran sesuai filter yang dipilih.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
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
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>
                        <span class="fw-700" style="font-weight:700;font-family:monospace;font-size:.8rem;">
                            {{ $row->pengajuan->kode_pengajuan ?? '-' }}
                        </span>
                    </td>
                    <td class="fw-600" style="font-weight:600;">{{ $row->pengajuan->penerima->nama ?? '-' }}</td>
                    <td>
                        @foreach($row->pengajuan->jenisBantuan ?? [] as $jb)
                        <span class="badge bg-success-subtle text-success rounded-pill me-1" style="font-size:.7rem;">{{ $jb->nama_bantuan }}</span>
                        @endforeach
                    </td>
                    <td>{{ $row->petugas->name ?? '-' }}</td>
                    <td>
                        <span class="fw-600 text-success" style="font-weight:600;">
                            {{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}
                        </span>
                    </td>
                    <td>
                        <span class="badge rounded-pill bg-success" style="font-size:.72rem;padding:.3rem .75rem;">
                            <i class="bi bi-check-circle-fill me-1"></i>Selesai
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
