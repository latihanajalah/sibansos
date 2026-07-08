@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Detail Jenis Bantuan</h2>
        <p class="text-muted mb-0">Informasi lengkap jenis bantuan sosial.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('jenis-bantuan.edit', $jenisBantuan) }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-pencil"></i> Edit
        </a>
        <a href="{{ route('jenis-bantuan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<x-breadcrumb :items="['Master Jenis Bantuan' => route('jenis-bantuan.index'), 'Detail Jenis Bantuan' => '#']" />

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card card-saas border-0 overflow-hidden">
            {{-- Header Band --}}
            <div class="p-4 d-flex align-items-center gap-4" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <div class="bg-white text-success rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0"
                     style="width: 72px; height: 72px; font-size: 1.5rem;">
                    <i class="bi bi-gift-fill fs-3 text-success"></i>
                </div>
                <div>
                    <span class="badge bg-white text-success fw-bold px-3 py-1.5 mb-1 fs-6">{{ $jenisBantuan->kode }}</span>
                    <h4 class="text-white fw-bold mb-0">{{ $jenisBantuan->nama_bantuan }}</h4>
                </div>
            </div>

            {{-- Detail Fields --}}
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Kode Bantuan</p>
                        <p class="fw-bold text-dark mb-0 fs-5">{{ $jenisBantuan->kode }}</p>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Nama Bantuan</p>
                        <p class="fw-medium text-dark mb-0">{{ $jenisBantuan->nama_bantuan }}</p>
                    </div>
                    <div class="col-12">
                        <p class="text-muted small mb-1">Deskripsi</p>
                        <div class="p-3 bg-light rounded border-light text-dark" style="min-height: 80px; font-size: 0.95rem;">
                            {{ $jenisBantuan->deskripsi ?? 'Tidak ada deskripsi.' }}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Status</p>
                        @if($jenisBantuan->status)
                            <span class="badge bg-success-subtle text-success fs-6 px-3 py-2">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-danger-subtle text-danger fs-6 px-3 py-2">
                                <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Nonaktif
                            </span>
                        @endif
                    </div>
                    <div class="col-sm-6">
                        <p class="text-muted small mb-1">Tanggal Dibuat</p>
                        <p class="fw-medium text-dark mb-0">{{ $jenisBantuan->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 pt-4 mt-3 border-top">
                    <a href="{{ route('jenis-bantuan.edit', $jenisBantuan) }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-pencil"></i> Edit Jenis Bantuan
                    </a>
                    @if(auth()->user()->role === 'super_admin')
                        <form action="{{ route('jenis-bantuan.destroy', $jenisBantuan) }}" method="POST" id="deleteForm">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-outline-danger d-flex align-items-center gap-2" id="btnDelete" data-name="{{ $jenisBantuan->nama_bantuan }}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </form>
                    @endif
                </div>
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
            title: 'Hapus Jenis Bantuan?',
            html: `Jenis bantuan <strong>${this.dataset.name}</strong> akan dihapus secara permanen.`,
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
