@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Isi Survei Lapangan</h2>
        <p class="text-muted mb-0">Pengajuan: <strong>{{ $pengajuan->kode_pengajuan }}</strong> — {{ $pengajuan->penerima->nama }}</p>
    </div>
    <a href="{{ route('pengajuan.show', $pengajuan) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali ke Pengajuan
    </a>
</div>

<x-breadcrumb :items="['Pengajuan Bantuan' => route('pengajuan.index'), 'Survei Lapangan' => route('survei.index'), 'Isi Survei' => '#']" />

{{-- Info Banner --}}
<div class="alert alert-info border-0 d-flex align-items-start gap-3 mb-4" style="background: rgba(13,110,253,0.06); border-radius: 12px;">
    <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
    <div>
        <strong class="text-primary">Informasi Pengajuan</strong><br>
        <span class="text-muted small">
            Penerima: <strong>{{ $pengajuan->penerima->nama }}</strong> &nbsp;|&nbsp;
            NIK: <strong>{{ $pengajuan->penerima->nik }}</strong> &nbsp;|&nbsp;
            Alamat: {{ $pengajuan->penerima->desa }}, Kec. {{ $pengajuan->penerima->kecamatan }}
        </span>
    </div>
</div>

<form action="{{ route('survei.store') }}" method="POST" enctype="multipart/form-data" id="form-survei">
    @csrf
    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

    @include('survei._form', ['survei' => null])

    {{-- Submit --}}
    <div class="d-flex justify-content-end gap-3 mt-2">
        <a href="{{ route('pengajuan.show', $pengajuan) }}" class="btn btn-outline-secondary px-5">Batal</a>
        <button type="submit" class="btn btn-primary px-5 d-flex align-items-center gap-2">
            <i class="bi bi-send-check"></i> Simpan Survei
        </button>
    </div>
</form>

@endsection
