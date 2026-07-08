@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Detail Survei Lapangan</h2>
        <p class="text-muted mb-0">Kode Pengajuan: <strong>{{ $survei->pengajuan->kode_pengajuan }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->role === 'petugas'
            && $survei->pengajuan->petugas_id === auth()->id()
            && $survei->pengajuan->status === 'menunggu_verifikasi')
            <a href="{{ route('survei.edit', $survei) }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-pencil"></i> Edit Survei
            </a>
        @endif
        <a href="{{ route('survei.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<x-breadcrumb :items="['Survei Lapangan' => route('survei.index'), 'Detail Survei' => '#']" />

<div class="row g-4">

    {{-- ── LEFT COLUMN ──────────────────────────── --}}
    <div class="col-lg-8">

        {{-- Status Banner --}}
        @php $status = $survei->pengajuan->status @endphp
        <div class="card border-0 mb-4 p-4 d-flex flex-row align-items-center gap-4"
             style="background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%); border-radius: 16px;">
            <div>
                <p class="text-muted small mb-1">Status Pengajuan</p>
                @if($status === 'menunggu_verifikasi')
                    <span class="badge bg-info-subtle text-info fs-6 px-3 py-2">Menunggu Verifikasi</span>
                @elseif($status === 'disetujui')
                    <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">Disetujui</span>
                @elseif($status === 'ditolak')
                    <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">Ditolak</span>
                @else
                    <span class="badge bg-secondary-subtle text-secondary fs-6 px-3 py-2 text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                @endif
            </div>
            <div class="vr d-none d-md-block opacity-25"></div>
            <div>
                <p class="text-muted small mb-1">Tanggal Survei</p>
                <p class="fw-bold text-dark mb-0">{{ $survei->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="vr d-none d-md-block opacity-25"></div>
            <div>
                <p class="text-muted small mb-1">Petugas</p>
                <p class="fw-bold text-dark mb-0">{{ $survei->pengajuan->petugas->nama ?? 'N/A' }}</p>
            </div>
        </div>

        {{-- Accordion Detail Survei --}}
        <div class="accordion border-0" id="accordionSurvei">

            {{-- Kondisi Rumah & Bangunan --}}
            <div class="accordion-item card-saas border-0 mb-3" style="border-radius: 12px; overflow: hidden;">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#colRumah">
                        <i class="bi bi-house-door text-primary me-2"></i> Kondisi Rumah & Bangunan
                    </button>
                </h2>
                <div id="colRumah" class="accordion-collapse collapse show" data-bs-parent="#accordionSurvei">
                    <div class="accordion-body">
                        <div class="row g-3">
                            @php
                                $rumahRows = [
                                    ['Status Rumah',       $survei->status_rumah],
                                    ['Kepemilikan Rumah',  $survei->kepemilikan_rumah],
                                    ['Jenis Lantai',       $survei->jenis_lantai],
                                    ['Jenis Dinding',      $survei->jenis_dinding],
                                    ['Jenis Atap',         $survei->jenis_atap],
                                ];
                            @endphp
                            @foreach($rumahRows as [$label, $value])
                                <div class="col-sm-4">
                                    <p class="text-muted small mb-1">{{ $label }}</p>
                                    <p class="fw-medium text-dark mb-0">{{ $value ?? '-' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Penghuni & Ekonomi --}}
            <div class="accordion-item card-saas border-0 mb-3" style="border-radius: 12px; overflow: hidden;">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#colEkonomi">
                        <i class="bi bi-wallet2 text-info me-2"></i> Data Penghuni & Ekonomi
                    </button>
                </h2>
                <div id="colEkonomi" class="accordion-collapse collapse" data-bs-parent="#accordionSurvei">
                    <div class="accordion-body">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Jumlah Kamar</p>
                                <p class="fw-medium text-dark mb-0">{{ $survei->jumlah_kamar }}</p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Jumlah Penghuni</p>
                                <p class="fw-medium text-dark mb-0">{{ $survei->jumlah_penghuni }} orang</p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Jumlah Tanggungan</p>
                                <p class="fw-medium text-dark mb-0">{{ $survei->jumlah_tanggungan }} orang</p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Pekerjaan</p>
                                <p class="fw-medium text-dark mb-0">{{ $survei->pekerjaan }}</p>
                            </div>
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Penghasilan / Bulan</p>
                                <p class="fw-bold text-dark mb-0">Rp {{ number_format($survei->penghasilan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kepemilikan Aset --}}
            <div class="accordion-item card-saas border-0 mb-3" style="border-radius: 12px; overflow: hidden;">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold" type="button"
                            data-bs-toggle="collapse" data-bs-target="#colAset">
                        <i class="bi bi-boxes text-warning me-2"></i> Kepemilikan Aset
                    </button>
                </h2>
                <div id="colAset" class="accordion-collapse collapse" data-bs-parent="#accordionSurvei">
                    <div class="accordion-body">
                        <div class="row g-3">
                            @php
                                $asets = [
                                    ['Memiliki Motor',       $survei->punya_motor,  'bi-scooter'],
                                    ['Memiliki Mobil',       $survei->punya_mobil,  'bi-car-front'],
                                    ['Memiliki Sawah/Ladang',$survei->punya_sawah,  'bi-flower1'],
                                    ['Memiliki Ternak',      $survei->punya_ternak, 'bi-egg-fried'],
                                ];
                            @endphp
                            @foreach($asets as [$label, $val, $icon])
                                <div class="col-sm-3">
                                    <div class="card p-3 border-light text-center h-100"
                                         style="background: {{ $val ? 'rgba(25,135,84,0.06)' : '#f8f9fa' }}; border-radius: 10px;">
                                        <i class="bi {{ $icon }} fs-3 mb-2 {{ $val ? 'text-success' : 'text-muted' }}"></i>
                                        <p class="small fw-medium mb-1 text-dark">{{ $label }}</p>
                                        @if($val)
                                            <span class="badge bg-success-subtle text-success">Ya</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Tidak</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Catatan --}}
            @if($survei->catatan)
                <div class="accordion-item card-saas border-0 mb-3" style="border-radius: 12px; overflow: hidden;">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold" type="button"
                                data-bs-toggle="collapse" data-bs-target="#colCatatan">
                            <i class="bi bi-journal-text text-secondary me-2"></i> Catatan Survei
                        </button>
                    </h2>
                    <div id="colCatatan" class="accordion-collapse collapse" data-bs-parent="#accordionSurvei">
                        <div class="accordion-body">
                            <div class="p-3 bg-light rounded" style="min-height: 80px; white-space: pre-wrap;">{{ $survei->catatan }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Galeri Foto --}}
        <div class="card card-saas border-0 p-4 mt-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-camera text-primary me-2"></i> Galeri Foto Survei
            </h5>
            @if($survei->foto->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-image text-muted fs-2"></i>
                    <p class="text-muted small mt-2 mb-0">Belum ada foto yang diupload.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($survei->foto as $foto)
                        <div class="col-sm-4">
                            <a href="{{ asset('storage/' . $foto->file) }}" target="_blank" class="d-block text-decoration-none">
                                <div class="card border-light overflow-hidden" style="border-radius: 10px;">
                                    <img src="{{ asset('storage/' . $foto->file) }}"
                                         alt="{{ $foto->kategori }}"
                                         class="img-fluid"
                                         style="height: 150px; object-fit: cover; width: 100%;">
                                    <div class="p-2 text-center">
                                        <small class="fw-medium text-muted">{{ $foto->kategori }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ── RIGHT COLUMN ─────────────────────────── --}}
    <div class="col-lg-4">

        {{-- Data Penerima --}}
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge text-primary me-2"></i> Data Penerima
            </h5>
            <div class="row g-2">
                <div class="col-12">
                    <p class="text-muted small mb-1">Nama Lengkap</p>
                    <p class="fw-bold text-dark mb-0">{{ $survei->pengajuan->penerima->nama ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted small mb-1">NIK</p>
                    <p class="fw-medium text-dark mb-0" style="font-size: 0.85rem;">{{ $survei->pengajuan->penerima->nik ?? 'N/A' }}</p>
                </div>
                <div class="col-6">
                    <p class="text-muted small mb-1">No KK</p>
                    <p class="fw-medium text-dark mb-0" style="font-size: 0.85rem;">{{ $survei->pengajuan->penerima->no_kk ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="fw-medium text-dark mb-0" style="font-size: 0.85rem;">
                        {{ $survei->pengajuan->penerima->alamat }}, RT {{ $survei->pengajuan->penerima->rt }}/RW {{ $survei->pengajuan->penerima->rw }},
                        Desa {{ $survei->pengajuan->penerima->desa }}, Kec. {{ $survei->pengajuan->penerima->kecamatan }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Dokumen Pendukung --}}
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-file-earmark-text text-primary me-2"></i> Dokumen Pendukung
            </h5>
            @if($survei->pengajuan->dokumen->isEmpty())
                <div class="text-center py-3">
                    <i class="bi bi-folder-x text-muted fs-2"></i>
                    <p class="text-muted small mt-2 mb-0">Tidak ada dokumen.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-2">
                    @foreach($survei->pengajuan->dokumen as $dok)
                        <a href="{{ asset('storage/' . $dok->file) }}" target="_blank"
                           class="card p-2 border-light text-decoration-none d-flex flex-row align-items-center gap-2"
                           style="border-radius: 8px; transition: background 0.2s;"
                           onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                            <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                            <span class="text-dark small fw-medium">{{ $dok->nama_dokumen }}</span>
                            <i class="bi bi-box-arrow-up-right text-muted ms-auto" style="font-size: 0.75rem;"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Riwayat Status --}}
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i> Riwayat Status
            </h5>
            @if($survei->pengajuan->riwayatStatus->isEmpty())
                <div class="text-center py-3">
                    <i class="bi bi-journal-check text-muted fs-2"></i>
                    <p class="text-muted small mt-2 mb-0">Belum ada riwayat.</p>
                </div>
            @else
                <div class="position-relative ps-3 border-start ms-2">
                    @foreach($survei->pengajuan->riwayatStatus as $riwayat)
                        <div class="mb-3 position-relative">
                            <div class="position-absolute rounded-circle bg-primary"
                                 style="width: 10px; height: 10px; left: -20px; top: 6px;"></div>
                            <div class="small text-muted">{{ $riwayat->created_at->format('d M Y, H:i') }}</div>
                            <div class="fw-bold text-dark text-capitalize">{{ str_replace('_', ' ', $riwayat->status) }}</div>
                            @if($riwayat->catatan)
                                <div class="text-muted small">{{ $riwayat->catatan }}</div>
                            @endif
                            @if($riwayat->user)
                                <div class="text-muted" style="font-size: 0.75rem;">
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
