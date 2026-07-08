@extends('layouts.app')

@section('content')

<x-breadcrumb :items="['Verifikasi Admin' => '#']" />

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Verifikasi Pengajuan</h2>
        <p class="text-muted mb-0">Verifikasi hasil survei lapangan untuk menentukan status pengajuan bantuan sosial.</p>
    </div>
</div>

{{-- Filters Card --}}
<div class="card card-saas border-0 p-4 mb-4">
    <form method="GET" action="{{ route('verifikasi.index') }}">
        <div class="row g-3">
            {{-- Search --}}
            <div class="col-md-3">
                <label class="form-label small fw-medium text-muted">Cari Pengajuan</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Nama, NIK, kode..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-3">
                <label class="form-label small fw-medium text-muted">Status</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="revisi_survei"       {{ request('status') === 'revisi_survei' ? 'selected' : '' }}>Revisi Survei</option>
                    <option value="menunggu_persetujuan" {{ request('status') === 'menunggu_persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                    <option value="ditolak_admin"        {{ request('status') === 'ditolak_admin' ? 'selected' : '' }}>Ditolak Admin</option>
                </select>
            </div>

            {{-- Petugas Filter --}}
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">Petugas</label>
                <select name="petugas_id" class="form-select">
                    <option value="">-- Semua Petugas --</option>
                    @foreach($petugasList as $p)
                        <option value="{{ $p->id }}" {{ request('petugas_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Start Date --}}
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">Mulai Survei</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            {{-- End Date --}}
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">Akhir Survei</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            {{-- Actions --}}
            <div class="col-12 d-flex gap-2 justify-content-end mt-1">
                @if(request()->anyFilled(['search', 'status', 'petugas_id', 'start_date', 'end_date']))
                    <a href="{{ route('verifikasi.index') }}" class="btn btn-outline-secondary px-4">Reset Filter</a>
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
                    <th>Kode Pengajuan</th>
                    <th>Nama Penerima</th>
                    <th>Petugas</th>
                    <th>Status</th>
                    <th>Tanggal Survei</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengajuanList as $pengajuan)
                    <tr>
                        <td class="text-muted small">{{ $pengajuanList->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $pengajuan->kode_pengajuan }}</span>
                        </td>
                        <td class="fw-medium text-dark">{{ $pengajuan->penerima->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $pengajuan->petugas->nama ?? 'N/A' }}</td>
                        <td>
                            @if($pengajuan->status === 'menunggu_verifikasi')
                                <span class="badge bg-warning-subtle text-warning">Menunggu Verifikasi</span>
                            @elseif($pengajuan->status === 'revisi_survei')
                                <span class="badge bg-info-subtle text-info">Revisi Survei</span>
                            @elseif($pengajuan->status === 'menunggu_persetujuan')
                                <span class="badge bg-primary-subtle text-primary">Menunggu Persetujuan</span>
                            @elseif($pengajuan->status === 'ditolak_admin')
                                <span class="badge bg-danger-subtle text-danger">Ditolak Admin</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary text-capitalize">{{ str_replace('_', ' ', $pengajuan->status) }}</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $pengajuan->survei ? $pengajuan->survei->created_at->format('d M Y') : '-' }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                @if($pengajuan->status === 'menunggu_verifikasi')
                                    <a href="{{ route('verifikasi.show', $pengajuan) }}"
                                       class="btn btn-sm btn-primary px-3 py-1 d-flex align-items-center gap-1" title="Verifikasi">
                                        <i class="bi bi-shield-check"></i> Verifikasi
                                    </a>
                                @else
                                    <a href="{{ route('verifikasi.show', $pengajuan) }}"
                                       class="btn btn-sm btn-outline-secondary px-3 py-1 d-flex align-items-center gap-1" title="Detail">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <x-empty-state
                                title="Belum ada pengajuan untuk diverifikasi"
                                description="Pengajuan yang selesai disurvei oleh petugas lapangan akan muncul di sini."
                                icon="bi-shield-check" />
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
