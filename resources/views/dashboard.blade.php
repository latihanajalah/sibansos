@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold text-dark">Dashboard</h2>
    <p class="text-muted mb-0">Selamat datang kembali, {{ auth()->user()->nama }}!</p>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card card-saas p-4">
            <h5 class="fw-semibold mb-2">Informasi Akun</h5>
            <p class="text-muted mb-1"><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p class="text-muted mb-0"><strong>Hak Akses:</strong> <span class="badge bg-primary text-white text-capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</span></p>
        </div>
    </div>
</div>
@endsection
