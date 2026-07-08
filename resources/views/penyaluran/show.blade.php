@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Detail Penyaluran Bantuan</h2>
        <p class="text-muted mb-0">Kode Pengajuan: <strong>{{ $penyaluran->pengajuan->kode_pengajuan }}</strong></p>
    </div>
    <a href="{{ route('penyaluran.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<x-breadcrumb :items="['Penyaluran' => route('penyaluran.index'), 'Detail Penyaluran' => '#']" />

<div class="row g-4">
    {{-- Left Column: Data Penerima, Pengajuan, Jenis Bantuan, Data Penyaluran --}}
    <div class="col-lg-8">
        {{-- Data Penyaluran --}}
        <div class="card border-0 mb-4 p-4 d-flex flex-row align-items-center gap-4"
             style="background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%); border-radius: 16px;">
            <div>
                <p class="text-muted small mb-1">Status Penyaluran</p>
                <span class="badge bg-success text-white fs-6 px-3 py-2 text-capitalize">{{ $penyaluran->status }}</span>
            </div>
            <div class="vr opacity-25"></div>
            <div>
                <p class="text-muted small mb-1">Tanggal Disalurkan</p>
                <p class="fw-bold text-dark mb-0 fs-5">{{ $penyaluran->tanggal->format('d F Y') }}</p>
            </div>
            <div class="vr opacity-25"></div>
            <div>
                <p class="text-muted small mb-1">Petugas Penyalur</p>
                <p class="fw-bold text-dark mb-0">{{ $penyaluran->petugas->nama ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Detail Informasi --}}
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge text-primary me-2"></i> Penerima & Pengajuan
            </h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Nama Lengkap</p>
                    <p class="fw-bold text-dark mb-0">{{ $penyaluran->pengajuan->penerima->nama ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">NIK</p>
                    <p class="fw-semibold text-dark mb-0">{{ $penyaluran->pengajuan->penerima->nik ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">No KK</p>
                    <p class="fw-medium text-dark mb-0">{{ $penyaluran->pengajuan->penerima->no_kk ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">No HP</p>
                    <p class="fw-medium text-dark mb-0">{{ $penyaluran->pengajuan->penerima->no_hp ?? '-' }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="fw-medium text-dark mb-0">
                        {{ $penyaluran->pengajuan->penerima->alamat ?? '' }}, RT {{ $penyaluran->pengajuan->penerima->rt }}/RW {{ $penyaluran->pengajuan->penerima->rw }},
                        Desa {{ $penyaluran->pengajuan->penerima->desa }}, Kec. {{ $penyaluran->pengajuan->penerima->kecamatan }}, Kab. {{ $penyaluran->pengajuan->penerima->kabupaten }}
                    </p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Jenis Bantuan Yang Diterima</p>
                    <div>
                        @foreach($penyaluran->pengajuan->jenisBantuan as $bantuan)
                            <span class="badge bg-primary-light text-primary fs-6 px-3 py-2 me-1 mb-1">
                                <strong class="text-dark">{{ $bantuan->kode }}</strong> - {{ $bantuan->nama_bantuan }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Catatan Penyaluran --}}
        @if($penyaluran->catatan)
            <div class="card card-saas border-0 p-4 mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="bi bi-journal-text text-primary me-2"></i> Catatan Penyaluran
                </h5>
                <div class="p-3 bg-light rounded text-dark" style="white-space: pre-wrap;">{{ $penyaluran->catatan }}</div>
            </div>
        @endif

        {{-- Bukti Penyaluran --}}
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-file-earmark-text text-primary me-2"></i> Bukti Penyaluran (Dokumen & Foto)
            </h5>
            @php
                $buktiDocs = $penyaluran->pengajuan->dokumen->where('nama_dokumen', 'Bukti Penyaluran');
            @endphp

            @if($buktiDocs->isEmpty())
                <p class="text-muted small">Tidak ada file bukti penyaluran diupload.</p>
            @else
                <div class="row g-3">
                    @foreach($buktiDocs as $dok)
                        @php
                            $ext = strtolower(pathinfo($dok->file, PATHINFO_EXTENSION));
                            $isImage = in_array($ext, ['jpg', 'jpeg', 'png']);
                        @endphp
                        <div class="col-sm-6 col-12">
                            <div class="card border-light h-100">
                                @if($isImage)
                                    <img src="{{ asset('storage/' . $dok->file) }}" class="card-img-top object-fit-cover" style="height: 180px;" alt="Bukti Penyaluran">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 4rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body p-3 d-flex justify-content-between align-items-center">
                                    <span class="text-truncate fw-medium text-dark small" style="max-width: 150px;">{{ $dok->nama_dokumen }} ({{ strtoupper($ext) }})</span>
                                    <a href="{{ asset('storage/' . $dok->file) }}" target="_blank" class="btn btn-sm btn-outline-primary px-3 py-1">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Buka File
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Right Column: Riwayat Status --}}
    <div class="col-lg-4">
        {{-- Riwayat Status --}}
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i> Riwayat Status Pengajuan
            </h5>
            @if($penyaluran->pengajuan->riwayatStatus->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-journal-x text-muted fs-1"></i>
                    <p class="text-muted small mt-2 mb-0">Belum ada riwayat status.</p>
                </div>
            @else
                <div class="position-relative ps-3 border-start ms-2">
                    @foreach($penyaluran->pengajuan->riwayatStatus as $riwayat)
                        <div class="mb-3 position-relative">
                            <div class="position-absolute rounded-circle bg-primary" style="width: 10px; height: 10px; left: -20px; top: 6px;"></div>
                            <div class="small text-muted">{{ $riwayat->created_at->format('d M Y, H:i') }}</div>
                            <div class="fw-bold text-dark text-capitalize">{{ str_replace('_', ' ', $riwayat->status) }}</div>
                            @if($riwayat->catatan)
                                <div class="text-muted small mb-1">{{ $riwayat->catatan }}</div>
                            @endif
                            @if($riwayat->user)
                                <div class="text-muted small" style="font-size: 0.75rem;">
                                    <i class="bi bi-person me-1"></i>{{ $riwayat->user->nama }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('css')
<style>
    .bg-primary-light { background-color: rgba(var(--bs-primary-rgb), 0.1); }
</style>
@endpush
