@extends('layouts.app')

@section('content')

<x-breadcrumb :items="['Pengajuan Bantuan' => '#']" />

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Pengajuan Bantuan Sosial</h2>
        <p class="text-muted mb-0">Daftar usulan calon penerima bantuan sosial.</p>
    </div>
    @if(auth()->user()->role === 'petugas')
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary d-flex align-items-center gap-2 px-4">
            <i class="bi bi-plus-lg"></i> Buat Pengajuan
        </a>
    @endif
</div>

{{-- Filters Card --}}
<div class="card card-saas border-0 p-4 mb-4">
    <form method="GET" action="{{ route('pengajuan.index') }}">
        <div class="row g-3">
            {{-- Search Input --}}
            <div class="col-md-4">
                <label class="form-label small fw-medium text-muted">Cari Pengajuan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text"
                           name="search"
                           class="form-control border-start-0 ps-0"
                           placeholder="Cari kode, NIK, nama..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_survei" {{ request('status') === 'menunggu_survei' ? 'selected' : '' }}>Menunggu Survei</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Start Date --}}
            <div class="col-md-2.5">
                <label class="form-label small fw-medium text-muted">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            {{-- End Date --}}
            <div class="col-md-2.5">
                <label class="form-label small fw-medium text-muted">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            {{-- Actions --}}
            <div class="col-12 d-flex gap-2 justify-content-end mt-4">
                @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('pengajuan.index') }}" class="btn btn-outline-secondary px-4">Reset Filter</a>
                @endif
                <button type="submit" class="btn btn-primary px-4">Terapkan Filter</button>
            </div>
        </div>
    </form>
</div>

{{-- Table Card --}}
<div class="card card-saas border-0 p-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="text-muted small" style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.04em;">
                    <th style="width: 48px;">#</th>
                    <th>Kode</th>
                    <th>Nama Penerima</th>
                    <th>NIK</th>
                    <th>Jenis Bantuan</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th class="text-center" style="width: 140px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanList as $pengajuan)
                    <tr>
                        <td class="text-muted small">{{ $pengajuanList->firstItem() + $loop->index }}</td>
                        <td><span class="fw-bold text-dark">{{ $pengajuan->kode_pengajuan }}</span></td>
                        <td class="fw-medium text-dark">{{ $pengajuan->penerima->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $pengajuan->penerima->nik ?? 'N/A' }}</td>
                        <td>
                            @foreach($pengajuan->jenisBantuan as $jb)
                                <span class="badge bg-primary-light text-primary me-1">{{ $jb->kode }}</span>
                            @endforeach
                        </td>
                        <td class="text-muted small">{{ $pengajuan->petugas->nama ?? 'N/A' }}</td>
                        <td>
                            @if($pengajuan->status === 'menunggu_survei')
                                <span class="badge bg-warning-subtle text-warning">Menunggu Survei</span>
                            @elseif($pengajuan->status === 'disetujui')
                                <span class="badge bg-success-subtle text-success">Disetujui</span>
                            @elseif($pengajuan->status === 'ditolak')
                                <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary text-capitalize">{{ str_replace('_', ' ', $pengajuan->status) }}</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('pengajuan.show', $pengajuan) }}"
                                   class="btn btn-sm btn-outline-secondary px-2 py-1"
                                   title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                {{-- Edit and Delete only for Petugas if status is menunggu_survei --}}
                                @if(auth()->user()->role === 'petugas' && $pengajuan->petugas_id === auth()->id() && $pengajuan->status === 'menunggu_survei')
                                    <a href="{{ route('pengajuan.edit', $pengajuan) }}"
                                       class="btn btn-sm btn-outline-primary px-2 py-1"
                                       title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <a href="{{ route('survei.create') }}?pengajuan_id={{ $pengajuan->id }}"
                                       class="btn btn-sm btn-primary px-2 py-1"
                                       title="Isi Survei">
                                        <i class="bi bi-clipboard2-check"></i>
                                    </a>
                                    <form action="{{ route('pengajuan.destroy', $pengajuan) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger px-2 py-1 btn-delete"
                                                data-code="{{ $pengajuan->kode_pengajuan }}"
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-5">
                            <x-empty-state
                                title="Belum ada pengajuan bantuan"
                                description="Usulan pengajuan bantuan sosial baru akan ditampilkan di sini."
                                icon="bi-file-earmark-text" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($pengajuanList->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <p class="text-muted small mb-0">
                Menampilkan {{ $pengajuanList->firstItem() }}–{{ $pengajuanList->lastItem() }} dari {{ $pengajuanList->total() }} pengajuan
            </p>
            {{ $pengajuanList->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection

@push('js')
{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.btn-delete').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const code = this.getAttribute('data-code');
        const form = this.closest('form');

        Swal.fire({
            title: 'Batalkan Pengajuan?',
            html: `Pengajuan <strong>${code}</strong> akan dihapus secara permanen dari sistem.<br>Tindakan ini tidak dapat dibatalkan.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
