@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Detail User</h2>
        <p class="text-muted mb-0">Informasi lengkap akun pengguna.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<x-breadcrumb :items="['Manajemen User' => route('users.index'), 'Detail User' => '#']" />

<div class="row justify-content-center">
    <div class="col-lg-full">
        <div class="card card-saas border-0 overflow-hidden">
            {{-- Profile Header Band --}}
            <div class="p-4 d-flex align-items-center gap-4" style="background: linear-gradient(135deg, #3b82f6 0%, #6366f1 100%);">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                     style="width: 72px; height: 72px; font-size: 1.5rem;">
                    {{ strtoupper(substr($user->nama, 0, 2)) }}
                </div>
                <div>
                    <h4 class="text-white fw-bold mb-1">{{ $user->nama }}</h4>
                    <span class="text-white-50 small">{{ $user->email }}</span>
                </div>
            </div>

            {{-- Detail Fields --}}
            <div class="p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Nama Lengkap</p>
                        <p class="fw-medium text-dark mb-0">{{ $user->nama }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Email</p>
                        <p class="fw-medium text-dark mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Nomor HP</p>
                        <p class="fw-medium text-dark mb-0">{{ $user->no_hp ?? '-' }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Role</p>
                        @switch($user->role)
                            @case('super_admin')
                                <span class="badge bg-danger fs-6">Super Admin</span>
                                @break
                            @case('admin')
                                <span class="badge bg-primary fs-6">Admin</span>
                                @break
                            @case('petugas')
                                <span class="badge bg-success fs-6">Petugas</span>
                                @break
                            @case('pimpinan')
                                <span class="badge bg-warning text-dark fs-6">Pimpinan</span>
                                @break
                        @endswitch
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Status</p>
                        @if($user->status === 'aktif')
                            <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Nonaktif
                            </span>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Terdaftar Sejak</p>
                        <p class="fw-medium text-dark mb-0">{{ $user->created_at->format('d F Y, H:i') }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Terakhir Diperbarui</p>
                        <p class="fw-medium text-dark mb-0">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 pt-4 mt-2 border-top">
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-pencil"></i> Edit User
                    </a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" id="deleteForm">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2" id="btnDelete" data-name="{{ $user->nama }}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('btnDelete').addEventListener('click', function () {
    Swal.fire({
        title: 'Hapus User?',
        html: `Akun <strong>${this.dataset.name}</strong> akan dihapus secara permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
    }).then((result) => {
        if (result.isConfirmed) document.getElementById('deleteForm').submit();
    });
});
</script>
@endpush
