@extends('layouts.app')

@section('content')

<x-breadcrumb :items="['Manajemen User' => '#']" />

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Manajemen User</h2>
        <p class="text-muted mb-0">Kelola semua akun pengguna sistem.</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-4">
        <i class="bi bi-plus-lg"></i> Tambah User
    </a>
</div>

{{-- Search Bar --}}
<div class="card card-saas border-0 p-4">
    <form method="GET" action="{{ route('users.index') }}" class="mb-4">
        <div class="input-group" style="max-width: 420px;">
            <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text"
                   name="search"
                   class="form-control border-start-0 ps-0"
                   placeholder="Cari nama, email, atau no hp..."
                   value="{{ request('search') }}">
            @if(request('search'))
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No HP</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td class="text-muted small">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                                     style="width: 36px; height: 36px; font-size: 0.85rem;">
                                    {{ strtoupper(substr($user->nama, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-medium text-dark">{{ $user->nama }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small">{{ $user->email }}</td>
                        <td class="text-muted small">{{ $user->no_hp }}</td>
                        <td>
                            @switch($user->role)
                                @case('super_admin')
                                    <span class="badge bg-danger">Super Admin</span>
                                    @break
                                @case('admin')
                                    <span class="badge bg-primary">Admin</span>
                                    @break
                                @case('petugas')
                                    <span class="badge bg-success">Petugas</span>
                                    @break
                                @case('pimpinan')
                                    <span class="badge bg-warning text-dark">Pimpinan</span>
                                    @break
                            @endswitch
                        </td>
                        <td>
                            @if($user->status === 'aktif')
                                <span class="badge bg-success-subtle text-success">Aktif</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('users.show', $user) }}"
                                   class="btn btn-sm btn-outline-secondary px-2 py-1"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}"
                                   class="btn btn-sm btn-outline-primary px-2 py-1"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger px-2 py-1 btn-delete"
                                            data-name="{{ $user->nama }}"
                                            title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-5">
                            <x-empty-state
                                title="Belum ada user"
                                description="Klik tombol 'Tambah User' untuk menambahkan pengguna baru."
                                icon="bi-people" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <p class="text-muted small mb-0">
                Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
            </p>
            {{ $users->links('pagination::bootstrap-5') }}
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
            title: 'Hapus User?',
            html: `Akun <strong>${name}</strong> akan dihapus secara permanen.<br>Tindakan ini tidak dapat dibatalkan.`,
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
