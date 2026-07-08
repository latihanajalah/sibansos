@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Catat Penyaluran Bantuan</h2>
        <p class="text-muted mb-0">Catat penyerahan bantuan sosial untuk pengajuan: <strong>{{ $pengajuan->kode_pengajuan }}</strong></p>
    </div>
    <a href="{{ route('pengajuan.show', $pengajuan) }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali ke Detail
    </a>
</div>

<x-breadcrumb :items="['Penyaluran' => route('penyaluran.index'), 'Catat Penyaluran' => '#']" />

<div class="row g-4">
    {{-- Left: recipient & application info --}}
    <div class="col-lg-5">
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge text-primary me-2"></i> Calon Penerima Bantuan
            </h5>
            <div class="row g-3">
                <div class="col-12">
                    <p class="text-muted small mb-1">Nama Lengkap</p>
                    <p class="fw-bold text-dark mb-0 fs-5">{{ $pengajuan->penerima->nama ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted small mb-1">NIK</p>
                    <p class="fw-semibold text-dark mb-0">{{ $pengajuan->penerima->nik ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted small mb-1">No KK</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->penerima->no_kk ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="fw-medium text-dark mb-0 small">
                        {{ $pengajuan->penerima->alamat ?? '' }}, RT {{ $pengajuan->penerima->rt }}/RW {{ $pengajuan->penerima->rw }},
                        Desa {{ $pengajuan->penerima->desa }}, Kec. {{ $pengajuan->penerima->kecamatan }}
                    </p>
                </div>
            </div>
        </div>

        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-gift text-primary me-2"></i> Jenis Bantuan Yang Disalurkan
            </h5>
            <div class="d-flex flex-column gap-2 mt-2">
                @foreach($pengajuan->jenisBantuan as $bantuan)
                    <div class="p-3 border rounded-3 bg-light">
                        <strong class="text-dark">{{ $bantuan->kode }}</strong>
                        <div class="text-muted small">{{ $bantuan->nama_bantuan }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right: form penyaluran --}}
    <div class="col-lg-7">
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-4">
                <i class="bi bi-truck text-primary me-2"></i> Form Penyaluran Bantuan
            </h5>

            <form action="{{ route('penyaluran.store') }}" method="POST" enctype="multipart/form-data" id="form-penyaluran">
                @csrf
                <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">

                {{-- Tanggal Penyaluran --}}
                <div class="mb-4">
                    <label for="tanggal" class="form-label fw-bold">Tanggal Penyaluran <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                           value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Petugas Lapangan --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Petugas Yang Menyerahkan</label>
                    <input type="text" class="form-control bg-light" value="{{ auth()->user()->nama }}" readonly disabled>
                    <small class="text-muted">Nama Anda tercatat otomatis sebagai petugas penyalur.</small>
                </div>

                {{-- Catatan --}}
                <div class="mb-4">
                    <label for="catatan" class="form-label fw-bold">Catatan Penyaluran <span class="text-muted fw-normal">(Opsional)</span></label>
                    <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4"
                              placeholder="Masukkan catatan penyerahan bantuan atau kendala di lapangan jika ada...">{{ old('catatan') }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Upload Bukti --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Upload Bukti Penyaluran <span class="text-danger">*</span></label>
                    <input type="file" name="bukti[]" class="form-control @error('bukti') is-invalid @enderror @error('bukti.*') is-invalid @enderror"
                           accept=".pdf,.jpg,.jpeg,.png" multiple required>
                    <small class="text-muted d-block mt-2">Dapat memilih lebih dari 1 file sekaligus. Format: PDF, JPG, JPEG, PNG. Maksimal: 5 MB per file.</small>
                    @error('bukti')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                    @error('bukti.*')
                        <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('pengajuan.show', $pengajuan) }}" class="btn btn-outline-secondary px-4">Batal</a>
                    <button type="submit" class="btn btn-primary px-5 fw-medium">
                        <i class="bi bi-check2-square me-1"></i> Simpan Penyaluran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
