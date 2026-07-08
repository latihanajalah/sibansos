@extends('layouts.app')

@section('content')

<x-breadcrumb :items="['Master Data Penerima' => '#']" />

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Master Data Penerima</h2>
        <p class="text-muted mb-0">Kelola informasi penerima manfaat bantuan sosial.</p>
    </div>
    <a href="{{ route('penerima.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-4">
        <i class="bi bi-plus-lg"></i> Tambah Penerima
    </a>
</div>

{{-- Search Bar --}}
<div class="card card-saas border-0 p-4">
    <form method="GET" action="{{ route('penerima.index') }}" class="mb-4">
        <div class="input-group" style="max-width: 420px;">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text"
                   name="search"
                   class="form-control border-start-0 ps-0"
                   placeholder="Cari NIK, KK, atau nama..."
                   value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('penerima.index') }}" class="btn btn-outline-secondary">
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
                                <span class="badge bg-primary-light text-primary">Laki-laki</span>
                            @else
                                <span class="badge bg-danger-light text-danger">Perempuan</span>
                            @endif
                        </td>
                        <td>{{ $penerima->desa }}</td>
                        <td>{{ $penerima->kecamatan }}</td>
                        <td>{{ $penerima->kabupaten }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('penerima.show', $penerima) }}"
                                   class="btn btn-sm btn-outline-secondary px-2 py-1"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('penerima.edit', $penerima) }}"
                                   class="btn btn-sm btn-outline-primary px-2 py-1"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('penerima.destroy', $penerima) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger px-2 py-1 btn-delete"
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
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <p class="text-muted small mb-0">
                Menampilkan {{ $penerimaList->firstItem() }}–{{ $penerimaList->lastItem() }} dari {{ $penerimaList->total() }} penerima
            </p>
            {{ $penerimaList->links('pagination::bootstrap-5') }}
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
