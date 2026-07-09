@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Tambah Data Penerima</h2>
        <p class="text-muted mb-0">Masukkan informasi lengkap penerima bantuan sosial baru.</p>
    </div>
    <a href="{{ route('penerima.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<x-breadcrumb :items="['Master Data Penerima' => route('penerima.index'), 'Tambah Penerima' => '#']" />

<div class="row justify-content-center">
    <div class="col-lg-full">
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-semibold text-dark mb-4 d-flex align-items-center gap-2">
                <i class="bi bi-person-plus text-primary"></i> Data Penerima Baru
            </h5>

            <form action="{{ route('penerima.store') }}" method="POST" novalidate>
                @csrf
                @include('penerima._form')

                <div class="d-flex justify-content-end gap-2 pt-2 border-top">
                    <a href="{{ route('penerima.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <i class="bi bi-check-lg"></i> Simpan Penerima
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
