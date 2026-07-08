@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Detail Pengajuan Bantuan</h2>
        <p class="text-muted mb-0">Informasi lengkap rincian pengajuan bantuan sosial.</p>
    </div>
    <div class="d-flex gap-2">
        @if(auth()->user()->role === 'petugas' && $pengajuan->petugas_id === auth()->id() && $pengajuan->status === 'menunggu_survei')
            <a href="{{ route('pengajuan.edit', $pengajuan) }}" class="btn btn-outline-primary d-flex align-items-center gap-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
            @if(!$pengajuan->survei)
                <a href="{{ route('survei.create') }}?pengajuan_id={{ $pengajuan->id }}"
                   class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-clipboard2-check"></i> Isi Survei
                </a>
            @endif
        @endif
        @if($pengajuan->survei)
            <a href="{{ route('survei.show', $pengajuan->survei) }}"
               class="btn btn-outline-info d-flex align-items-center gap-2">
                <i class="bi bi-clipboard2-data"></i> Lihat Survei
            </a>
        @endif
        @if(auth()->user()->role === 'petugas' && $pengajuan->status === 'siap_disalurkan')
            <a href="{{ route('penyaluran.create') }}?pengajuan_id={{ $pengajuan->id }}"
               class="btn btn-success d-flex align-items-center gap-2 text-white">
                <i class="bi bi-truck"></i> Salurkan Bantuan
            </a>
        @endif
        <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

</div>

<x-breadcrumb :items="['Pengajuan Bantuan' => route('pengajuan.index'), 'Detail Pengajuan' => '#']" />

<div class="row g-4">
    <!-- Left Column: Data Pengajuan & Bantuan -->
    <div class="col-lg-7">
        <!-- Data Pengajuan -->
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-file-earmark-text text-primary me-2"></i> Rincian Pengajuan
            </h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Kode Pengajuan</p>
                    <p class="fw-bold text-dark mb-0 fs-5">{{ $pengajuan->kode_pengajuan }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Status Pengajuan</p>
                    <div>
                        @if($pengajuan->status === 'menunggu_survei')
                            <span class="badge bg-warning-subtle text-warning fs-6 px-3 py-2">Menunggu Survei</span>
                        @elseif($pengajuan->status === 'disetujui')
                            <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">Disetujui</span>
                        @elseif($pengajuan->status === 'ditolak')
                            <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">Ditolak</span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary fs-6 px-3 py-2 text-capitalize">{{ str_replace('_', ' ', $pengajuan->status) }}</span>
                        @endif
                    </div>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Tanggal Pengajuan</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->tanggal_pengajuan->format('d F Y') }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Petugas Pengaju</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->petugas->nama ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Keterangan</p>
                    <div class="p-3 bg-light rounded text-dark fs-6" style="min-height: 80px;">
                        {{ $pengajuan->keterangan ?? 'Tidak ada keterangan tambahan.' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Bantuan yang Diajukan -->
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-gift text-primary me-2"></i> Jenis Bantuan Yang Diajukan
            </h5>
            <div class="row g-3">
                @foreach($pengajuan->jenisBantuan as $bantuan)
                    <div class="col-md-6">
                        <div class="card p-3 border-light shadow-sm h-100">
                            <h6 class="fw-bold text-dark mb-1">{{ $bantuan->kode }}</h6>
                            <p class="text-dark small mb-2">{{ $bantuan->nama_bantuan }}</p>
                            @if($bantuan->deskripsi)
                                <small class="text-muted d-block mt-auto border-top pt-2" style="font-size: 0.75rem;">
                                    {{ Str::limit($bantuan->deskripsi, 80) }}
                                </small>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Right Column: Data Penerima & Riwayat Status -->
    <div class="col-lg-5">
        <!-- Data Penerima -->
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge text-primary me-2"></i> Data Penerima
            </h5>
            <div class="row g-3">
                <div class="col-12">
                    <p class="text-muted small mb-1">Nama Lengkap</p>
                    <p class="fw-bold text-dark mb-0">{{ $pengajuan->penerima->nama ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">NIK</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->penerima->nik ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">No KK</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->penerima->no_kk ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Alamat Lengkap</p>
                    <p class="fw-medium text-dark mb-0" style="font-size: 0.9rem;">
                        {{ $pengajuan->penerima->alamat ?? '' }}, RT {{ $pengajuan->penerima->rt ?? '' }} / RW {{ $pengajuan->penerima->rw ?? '' }}, Desa {{ $pengajuan->penerima->desa ?? '' }}, Kec. {{ $pengajuan->penerima->kecamatan ?? '' }}, Kab. {{ $pengajuan->penerima->kabupaten ?? '' }}
                    </p>
                </div>
                @if($pengajuan->penerima->no_hp ?? false)
                    <div class="col-12">
                        <p class="text-muted small mb-1">No HP Kontak</p>
                        <p class="fw-semibold text-dark mb-0">
                            <i class="bi bi-telephone text-success me-1"></i> {{ $pengajuan->penerima->no_hp }}
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Status -->
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-clock-history text-primary me-2"></i> Riwayat Status Pengajuan
            </h5>
            @if($pengajuan->riwayatStatus->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-journal-check text-muted fs-2"></i>
                    <p class="text-muted small mt-2 mb-0">Belum ada riwayat perubahan status.</p>
                </div>
            @else
                <div class="position-relative ps-3 border-start ms-2">
                    @foreach($pengajuan->riwayatStatus as $riwayat)
                        <div class="mb-3 position-relative">
                            <div class="position-absolute rounded-circle bg-primary" style="width: 10px; height: 10px; left: -20px; top: 6px;"></div>
                            <div class="small text-muted">{{ $riwayat->created_at->format('d M Y, H:i') }}</div>
                            <div class="fw-bold text-dark text-capitalize">{{ str_replace('_', ' ', $riwayat->status) }}</div>
                            @if($riwayat->keterangan)
                                <div class="text-muted small">{{ $riwayat->keterangan }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
