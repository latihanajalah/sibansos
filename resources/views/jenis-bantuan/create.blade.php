@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Tambah Jenis Bantuan</h2>
        <p class="text-muted mb-0">Isi data jenis bantuan baru untuk ditambahkan ke sistem.</p>
    </div>
    <a href="{{ route('jenis-bantuan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<x-breadcrumb :items="['Master Jenis Bantuan' => route('jenis-bantuan.index'), 'Tambah Jenis Bantuan' => '#']" />

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-semibold text-dark mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle text-primary"></i> Data Jenis Bantuan Baru
            </h5>

            <form action="{{ route('jenis-bantuan.store') }}" method="POST" novalidate>
                @csrf
                @include('jenis-bantuan._form')

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('jenis-bantuan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check-lg"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
