@extends('layouts.app')

@section('title', 'Manajemen User')

@push('css')
<style>
    /* ─── Table Card ─────────────────────────────── */
    .users-table-card {
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }
    .users-table thead tr th {
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
    .users-table tbody tr td {
        padding: .85rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .users-table tbody tr:last-child td { border-bottom: none; }
    .users-table tbody tr { transition: background .15s; }
    .users-table tbody tr:hover { background: #f8fafc; }

    .user-avatar {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        font-size: .8rem;
        color: #fff;
        flex-shrink: 0;
    }

    .role-badge, .status-badge {
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

<x-breadcrumb :items="['Manajemen User' => '#']" />

{{-- Page Header --}}
<div class="page-header mb-4">
    <div class="page-header-content">
        <h2>Manajemen User</h2>
        <p>Kelola semua akun pengguna sistem.</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Tambah User
        </a>
    </div>
</div>

{{-- ─── Stat Cards ─── --}}
@php
    $roleCounts = [
        'super_admin' => $users->getCollection()->where('role', 'super_admin')->count(),
        'admin'       => $users->getCollection()->where('role', 'admin')->count(),
        'petugas'     => $users->getCollection()->where('role', 'petugas')->count(),
        'pimpinan'    => $users->getCollection()->where('role', 'pimpinan')->count(),
    ];

    $userStatCards = [
        ['label' => 'Total User',  'value' => $users->total(),            'icon' => 'bi-people-fill',       'bg' => '#dbeafe', 'ic' => '#2563eb'],
        ['label' => 'Super Admin', 'value' => $roleCounts['super_admin'], 'icon' => 'bi-shield-lock-fill',  'bg' => '#fee2e2', 'ic' => '#dc2626'],
        ['label' => 'Admin',       'value' => $roleCounts['admin'],       'icon' => 'bi-person-badge-fill', 'bg' => '#dbeafe', 'ic' => '#2563eb'],
        ['label' => 'Petugas',     'value' => $roleCounts['petugas'],     'icon' => 'bi-person-workspace',  'bg' => '#dcfce7', 'ic' => '#16a34a'],
        ['label' => 'Pimpinan',    'value' => $roleCounts['pimpinan'],    'icon' => 'bi-award-fill',        'bg' => '#fef3c7', 'ic' => '#d97706'],
    ];
@endphp

<div class="row g-4 mb-5">
    @foreach($userStatCards as $card)
    <div class="col-6 col-lg-3 col-xl-2.4">
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
    <form method="GET" action="{{ route('users.index') }}">
        <div class="row g-3 align-items-end">
            {{-- Search Input --}}
            <div class="col-md-5">
                <label class="form-label">Cari User</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0 ps-0"
                           placeholder="Cari nama, email, atau no hp..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Role Filter --}}
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select name="role" class="form-select">
                    <option value="">-- Semua Role --</option>
                    <option value="super_admin" {{ request('role') === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="admin"       {{ request('role') === 'admin'       ? 'selected' : '' }}>Admin</option>
                    <option value="petugas"     {{ request('role') === 'petugas'     ? 'selected' : '' }}>Petugas</option>
                    <option value="pimpinan"    {{ request('role') === 'pimpinan'    ? 'selected' : '' }}>Pimpinan</option>
                </select>
            </div>

            {{-- Actions --}}
            <div class="col-md-4">
                <div class="filter-actions">
                    @if(request()->anyFilled(['search', 'role']))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
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
<div class="users-table-card">
    <div class="table-responsive">
        <table class="table users-table align-middle mb-0">
            <thead>
                <tr>
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
                    @php
                        $roleColors = [
                            'super_admin' => ['bg' => '#fee2e2', 'fg' => '#dc2626', 'label' => 'Super Admin', 'icon' => 'bi-shield-lock-fill'],
                            'admin'       => ['bg' => '#dbeafe', 'fg' => '#2563eb', 'label' => 'Admin',       'icon' => 'bi-person-badge-fill'],
                            'petugas'     => ['bg' => '#dcfce7', 'fg' => '#16a34a', 'label' => 'Petugas',      'icon' => 'bi-person-workspace'],
                            'pimpinan'    => ['bg' => '#fef3c7', 'fg' => '#d97706', 'label' => 'Pimpinan',     'icon' => 'bi-award-fill'],
                        ];
                        $rc = $roleColors[$user->role] ?? ['bg' => '#f1f5f9', 'fg' => '#64748b', 'label' => ucfirst($user->role), 'icon' => 'bi-person'];
                        $avatarColors = ['#2563eb', '#16a34a', '#d97706', '#dc2626', '#7c3aed', '#0891b2'];
                        $avatarColor = $avatarColors[$user->id % count($avatarColors)];
                    @endphp
                    <tr>
                        <td class="text-muted small">{{ $users->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="user-avatar" style="background:{{ $avatarColor }};">
                                    {{ strtoupper(substr($user->nama, 0, 2)) }}
                                </div>
                                <div class="fw-medium text-dark">{{ $user->nama }}</div>
                            </div>
                        </td>
                        <td class="text-muted small">{{ $user->email }}</td>
                        <td class="text-muted small">{{ $user->no_hp ?: '-' }}</td>
                        <td>
                            <span class="role-badge" style="background:{{ $rc['bg'] }};color:{{ $rc['fg'] }};">
                                <i class="bi {{ $rc['icon'] }}"></i> {{ $rc['label'] }}
                            </span>
                        </td>
                        <td>
                            @if($user->status === 'aktif')
                                <span class="status-badge" style="background:#dcfce7;color:#16a34a;">
                                    <i class="bi bi-check-circle-fill"></i> Aktif
                                </span>
                            @else
                                <span class="status-badge" style="background:#fee2e2;color:#dc2626;">
                                    <i class="bi bi-x-circle-fill"></i> Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('users.show', $user) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-secondary"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user) }}"
                                   class="btn btn-sm btn-icon-action btn-outline-primary"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="btn btn-sm btn-icon-action btn-outline-danger btn-delete"
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
        <div class="px-4 py-3 border-top">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <p class="text-muted small mb-0">
                    Menampilkan {{ $users->firstItem() }}–{{ $users->lastItem() }} dari {{ $users->total() }} user
                </p>
                {{ $users->links('pagination::bootstrap-5') }}
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