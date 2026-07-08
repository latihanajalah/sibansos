@extends('layouts.app')

@section('content')

<x-breadcrumb :items="['Jenis Bantuan' => '#']" />

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Master Jenis Bantuan</h2>
        <p class="text-muted mb-0">Kelola daftar bantuan sosial yang terintegrasi di sistem.</p>
    </div>
    <a href="{{ route('jenis-bantuan.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-4">
        <i class="bi bi-plus-lg"></i> Tambah Jenis Bantuan
    </a>
</div>

{{-- Search Bar --}}
<div class="card card-saas border-0 p-4">
    <form method="GET" action="{{ route('jenis-bantuan.index') }}" class="mb-4">
        <div class="input-group" style="max-width: 420px;">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text"
                   name="search"
                   class="form-control border-start-0 ps-0"
                   placeholder="Cari kode atau nama bantuan..."
                   value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('jenis-bantuan.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            @endif
            <button type="submit" class="btn btn-primary px-4">Cari</button>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-muted small" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em;">
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
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $item->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('jenis-bantuan.show', $item) }}"
                                   class="btn btn-sm btn-outline-secondary px-2 py-1"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('jenis-bantuan.edit', $item) }}"
                                   class="btn btn-sm btn-outline-primary px-2 py-1"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(auth()->user()->role === 'super_admin')
                                    <form action="{{ route('jenis-bantuan.destroy', $item) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger px-2 py-1 btn-delete"
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
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <p class="text-muted small mb-0">
                Menampilkan {{ $jenisBantuanList->firstItem() }}–{{ $jenisBantuanList->lastItem() }} dari {{ $jenisBantuanList->total() }} jenis bantuan
            </p>
            {{ $jenisBantuanList->links('pagination::bootstrap-5') }}
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
