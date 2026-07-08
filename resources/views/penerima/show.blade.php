@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Detail Penerima</h2>
        <p class="text-muted mb-0">Informasi profil lengkap penerima manfaat.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('penerima.edit', $penerima) }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('penerima.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<x-breadcrumb :items="['Master Data Penerima' => route('penerima.index'), 'Detail Penerima' => '#']" />

<div class="row g-4">
    <!-- Left Column: Identitas & Kontak -->
    <div class="col-lg-6">
        <!-- Identitas Card -->
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-bounding-box text-primary me-2"></i> Data Identitas
            </h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">NIK</p>
                    <p class="fw-semibold text-dark mb-0 fs-5">{{ $penerima->nik }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Nomor Kartu Keluarga (KK)</p>
                    <p class="fw-semibold text-dark mb-0 fs-5">{{ $penerima->no_kk }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Nama Lengkap</p>
                    <p class="fw-medium text-dark mb-0">{{ $penerima->nama }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Jenis Kelamin</p>
                    @if($penerima->jenis_kelamin === 'Laki-laki')
                        <span class="badge bg-primary-light text-primary">Laki-laki</span>
                    @else
                        <span class="badge bg-danger-light text-danger">Perempuan</span>
                    @endif
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Tempat Lahir</p>
                    <p class="fw-medium text-dark mb-0">{{ $penerima->tempat_lahir }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Tanggal Lahir</p>
                    <p class="fw-medium text-dark mb-0">
                        {{ $penerima->tanggal_lahir ? $penerima->tanggal_lahir->format('d F Y') : '-' }}
                        @if($penerima->tanggal_lahir)
                            <span class="text-muted small">({{ $penerima->tanggal_lahir->age }} Tahun)</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Kontak Card -->
        <div class="card card-saas border-0 p-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-telephone text-primary me-2"></i> Kontak
            </h5>
            <div class="row g-3">
                <div class="col-12">
                    <p class="text-muted small mb-1">Nomor HP</p>
                    @if($penerima->no_hp)
                        <p class="fw-semibold text-dark mb-0">
                            <i class="bi bi-whatsapp text-success me-1"></i> {{ $penerima->no_hp }}
                        </p>
                    @else
                        <p class="text-muted mb-0">Tidak ada nomor HP terdaftar.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Alamat & metadata -->
    <div class="col-lg-6">
        <!-- Alamat Card -->
        <div class="card card-saas border-0 p-4 h-100">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-geo-alt text-primary me-2"></i> Data Alamat
            </h5>
            <div class="row g-3">
                <div class="col-12">
                    <p class="text-muted small mb-1">Alamat Jalan / Dusun</p>
                    <p class="fw-medium text-dark mb-0">{{ $penerima->alamat }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">RT / RW</p>
                    <p class="fw-medium text-dark mb-0">RT {{ $penerima->rt }} / RW {{ $penerima->rw }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Desa / Kelurahan</p>
                    <p class="fw-medium text-dark mb-0">{{ $penerima->desa }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Kecamatan</p>
                    <p class="fw-medium text-dark mb-0">{{ $penerima->kecamatan }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Kabupaten / Kota</p>
                    <p class="fw-medium text-dark mb-0">{{ $penerima->kabupaten }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Provinsi</p>
                    <p class="fw-medium text-dark mb-0">{{ $penerima->provinsi }}</p>
                </div>
                <div class="col-12 pt-3 border-top mt-4">
                    <p class="text-muted small mb-1">Tanggal Terdaftar</p>
                    <p class="fw-medium text-dark mb-2" style="font-size: 0.9rem;">
                        {{ $penerima->created_at->format('d F Y, H:i') }}
                    </p>
                    
                    <p class="text-muted small mb-1">Terakhir Diubah</p>
                    <p class="fw-medium text-dark mb-0" style="font-size: 0.9rem;">
                        {{ $penerima->updated_at->format('d F Y, H:i') }}
                    </p>
                </div>
            </div>
            
            <div class="d-flex gap-2 pt-4 mt-4 border-top">
                <form action="{{ route('penerima.destroy', $penerima) }}" method="POST" id="deleteForm">
                    @csrf @method('DELETE')
                    <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2" id="btnDelete" data-name="{{ $penerima->nama }}">
                        <i class="bi bi-trash"></i> Hapus Penerima
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const btnDelete = document.getElementById('btnDelete');
if (btnDelete) {
    btnDelete.addEventListener('click', function () {
        Swal.fire({
            title: 'Hapus Data Penerima?',
            html: `Data <strong>${this.dataset.name}</strong> akan dihapus secara permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('deleteForm').submit();
        });
    });
}
</script>
@endpush
