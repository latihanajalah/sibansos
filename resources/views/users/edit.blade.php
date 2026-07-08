@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Edit User</h2>
        <p class="text-muted mb-0">Perbarui data pengguna <strong>{{ $user->nama }}</strong>.</p>
    </div>
    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<x-breadcrumb :items="['Manajemen User' => route('users.index'), 'Edit User' => '#']" />

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-semibold text-dark mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square text-primary"></i> Perbarui Data Pengguna
            </h5>

            <form action="{{ route('users.update', $user) }}" method="POST" novalidate>
                @csrf
                @method('PUT')
                @include('users._form')

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check-lg"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
