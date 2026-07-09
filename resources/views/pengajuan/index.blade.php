@extends('layouts.app')

@section('title', 'Pengajuan Bantuan')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .pengajuan-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .pengajuan-table thead tr th {
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
    .pengajuan-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .pengajuan-table tbody tr:last-child td { border-bottom: none; }
    .pengajuan-table tbody tr { transition: background .15s; }
    .pengajuan-table tbody tr:hover { background: #f8fafc; }

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

    .badge-jenis {
        font-size: .72rem;
        font-weight: 600;
        padding: .3rem .6rem;
        border-radius: 999px;
        background: #dbeafe;
        color: #2563eb;
        display: inline-flex;
        align-items: center;
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

<x-breadcrumb :items="['Pengajuan Bantuan' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Pengajuan Bantuan Sosial</h2>
        <p>Daftar usulan calon penerima bantuan sosial.</p>
    </div>
    <div class="page-header-actions">
        @if(auth()->user()->role === 'petugas')
            <a href="{{ route('pengajuan.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Buat Pengajuan
            </a>
        @endif
    </div>
</div>

{{-- Filters Card --}}
<div class="filter-card mb-4">
    <form method="GET" action="{{ route('pengajuan.index') }}">
        <div class="row g-3 align-items-end">
            {{-- Search Input --}}
            <div class="col-md-4">
                <label class="form-label">Cari Pengajuan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0 ps-0"
                           placeholder="Cari kode, NIK, nama..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_survei" {{ request('status') === 'menunggu_survei' ? 'selected' : '' }}>Menunggu Survei</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
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
                    @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary">
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
<div class="pengajuan-table-card">
    <div class="table-responsive">
        <table class="table pengajuan-table align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 48px;">#</th>
                    <th>Kode</th>
                    <th>Nama Penerima</th>
                    <th>NIK</th>
                    <th>Jenis Bantuan</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-center" style="width: 150px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanList as $pengajuan)
                    <tr>
                        <td class="text-muted small">{{ $pengajuanList->firstItem() + $loop->index }}</td>
                        <td><span class="fw-bold text-dark">{{ $pengajuan->kode_pengajuan }}</span></td>
                        <td class="fw-medium text-dark">{{ $pengajuan->penerima->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $pengajuan->penerima->nik ?? 'N/A' }}</td>
                        <td>
                            @foreach($pengajuan->jenisBantuan as $jb)
                                <span class="badge-jenis me-1">{{ $jb->kode }}</span>
                            @endforeach
                        </td>
                        <td class="text-muted small">{{ $pengajuan->petugas->nama ?? 'N/A' }}</td>
                        <td>
                            @php
                                [$statusLabel, $statusColor, $statusIcon] = \App\Helpers\StatusHelper::label($pengajuan->status);
                                $statusText = in_array($statusColor, ['warning', 'light']) ? 'dark' : 'white';
                            @endphp
                            <span class="status-badge badge bg-{{ $statusColor }} text-{{ $statusText }}">
                                <i class="bi {{ $statusIcon }}"></i> {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('pengajuan.show', $pengajuan) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-secondary"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Edit and Delete only for Petugas if status is menunggu_survei --}}
                                @if(auth()->user()->role === 'petugas' && $pengajuan->petugas_id === auth()->id() && $pengajuan->status === 'menunggu_survei')
                                    <a href="{{ route('pengajuan.edit', $pengajuan) }}"
                                       class="btn btn-sm btn-icon-action btn-outline-primary"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('survei.create') }}?pengajuan_id={{ $pengajuan->id }}"
                                       class="btn btn-sm btn-icon-action btn-primary"
                                       title="Isi Survei">
                                        <i class="bi bi-clipboard2-check"></i>
                                    </a>
                                    <form action="{{ route('pengajuan.destroy', $pengajuan) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-icon-action btn-outline-danger btn-delete"
                                                data-code="{{ $pengajuan->kode_pengajuan }}"
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
                        <td colspan="9" class="py-5">
                            <x-empty-state
                                title="Belum ada pengajuan bantuan"
                                description="Usulan pengajuan bantuan sosial baru akan ditampilkan di sini."
                                icon="bi-file-earmark-text" />
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

@push('js')
{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const code = this.getAttribute('data-code');
        const form = this.closest('form');

        Swal.fire({
            title: 'Batalkan Pengajuan?',
            html: `Pengajuan <strong>${code}</strong> akan dihapus secara permanen dari sistem.<br>Tindakan ini tidak dapat dibatalkan.`,
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