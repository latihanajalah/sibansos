@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Edit Survei Lapangan</h2>
        <p class="text-muted mb-0">Pengajuan: <strong>{{ $pengajuan->kode_pengajuan }}</strong> — {{ $pengajuan->penerima->nama }}</p>
    </div>
    <a href="{{ route('survei.show', $survei) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali ke Detail
    </a>
</div>

<x-breadcrumb :items="['Survei Lapangan' => route('survei.index'), 'Edit Survei' => '#']" />

{{-- Warning banner --}}
<div class="alert alert-warning border-0 d-flex align-items-start gap-3 mb-4" style="border-radius: 12px;">
    <i class="bi bi-exclamation-triangle-fill text-warning fs-5 mt-1"></i>
    <div>
        <strong>Perhatian</strong><br>
        <span class="small">Survei hanya dapat diubah selama status pengajuan masih <strong>Menunggu Verifikasi</strong>. Setelah diverifikasi, data tidak dapat diubah.</span>
    </div>
</div>

<form action="{{ route('survei.update', $survei) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('survei._form', ['survei' => $survei, 'pengajuan' => $pengajuan])

    {{-- Submit --}}
    <div class="d-flex justify-content-end gap-3 mt-2">
        <a href="{{ route('survei.show', $survei) }}" class="btn btn-outline-secondary px-5">Batal</a>
        <button type="submit" class="btn btn-primary px-5 d-flex align-items-center gap-2">
            <i class="bi bi-floppy"></i> Simpan Perubahan
        </button>
    </div>
</form>

@endsection
