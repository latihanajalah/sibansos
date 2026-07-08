@extends('layouts.app')

@section('content')

<x-breadcrumb :items="['Survei Lapangan' => '#']" />

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Survei Lapangan</h2>
        <p class="text-muted mb-0">Daftar hasil survei lapangan pengajuan bantuan sosial.</p>
    </div>
</div>

@if(auth()->user()->role === 'petugas')
    @php
        $pendingSurvei = \App\Models\Pengajuan::where('petugas_id', auth()->id())
            ->where('status', 'menunggu_survei')
            ->with('penerima')
            ->latest()
            ->get();
    @endphp
    @if($pendingSurvei->count() > 0)
    <div class="alert alert-warning border-0 shadow-sm mb-4 d-flex align-items-start gap-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 text-warning"></i>
        <div class="flex-grow-1">
            <div class="fw-bold mb-2">{{ $pendingSurvei->count() }} Pengajuan Menunggu Survei</div>
            <div class="d-flex flex-wrap gap-2">
                @foreach($pendingSurvei as $p)
                    <a href="{{ route('survei.create') }}?pengajuan_id={{ $p->id }}"
                       class="btn btn-sm btn-warning fw-medium">
                        <i class="bi bi-clipboard2-check me-1"></i>
                        Isi Survei: {{ $p->kode_pengajuan }} – {{ $p->penerima->nama ?? 'N/A' }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info border-0 shadow-sm mb-4 d-flex align-items-center gap-2" role="alert">
        <i class="bi bi-info-circle-fill text-info"></i>
        <span>Semua pengajuan Anda sudah disurvei. Halaman ini menampilkan riwayat survei yang sudah pernah diisi.</span>
    </div>
    @endif
@endif

{{-- Filters Card --}}
<div class="card card-saas border-0 p-4 mb-4">
    <form method="GET" action="{{ route('survei.index') }}">
        <div class="row g-3">
            {{-- Search --}}
            <div class="col-md-4">
                <label class="form-label small fw-medium text-muted">Cari Survei</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                           placeholder="Kode pengajuan, NIK, nama..."
                           value="{{ request('search') }}">
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-3">
                <label class="form-label small fw-medium text-muted">Status Pengajuan</label>
                <select name="status" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <option value="menunggu_verifikasi" {{ request('status') === 'menunggu_verifikasi' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="disetujui"           {{ request('status') === 'disetujui'           ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak"             {{ request('status') === 'ditolak'             ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Start Date --}}
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            {{-- End Date --}}
            <div class="col-md-2">
                <label class="form-label small fw-medium text-muted">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            {{-- Actions --}}
            <div class="col-12 d-flex gap-2 justify-content-end mt-1">
                @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date']))
                    <a href="{{ route('survei.index') }}" class="btn btn-outline-secondary px-4">Reset Filter</a>
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
                    <th>Status Pengajuan</th>
                    <th>Tanggal Survei</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surveiList as $survei)
                    <tr>
                        <td class="text-muted small">{{ $surveiList->firstItem() + $loop->index }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $survei->pengajuan->kode_pengajuan ?? 'N/A' }}</span>
                        </td>
                        <td class="fw-medium text-dark">{{ $survei->pengajuan->penerima->nama ?? 'N/A' }}</td>
                        <td class="text-muted small">{{ $survei->pengajuan->petugas->nama ?? 'N/A' }}</td>
                        <td>
                            @php $status = $survei->pengajuan->status ?? '' @endphp
                            @if($status === 'menunggu_verifikasi')
                                <span class="badge bg-info-subtle text-info">Menunggu Verifikasi</span>
                            @elseif($status === 'disetujui')
                                <span class="badge bg-success-subtle text-success">Disetujui</span>
                            @elseif($status === 'ditolak')
                                <span class="badge bg-danger-subtle text-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $survei->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('survei.show', $survei) }}"
                                   class="btn btn-sm btn-outline-secondary px-2 py-1" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(auth()->user()->role === 'petugas'
                                    && $survei->pengajuan->petugas_id === auth()->id()
                                    && $survei->pengajuan->status === 'menunggu_verifikasi')
                                    <a href="{{ route('survei.edit', $survei) }}"
                                       class="btn btn-sm btn-outline-primary px-2 py-1" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-5">
                            <x-empty-state
                                title="Belum ada data survei"
                                description="Survei lapangan yang sudah diisi akan tampil di sini."
                                icon="bi-clipboard2-check" />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($surveiList->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <p class="text-muted small mb-0">
                Menampilkan {{ $surveiList->firstItem() }}–{{ $surveiList->lastItem() }} dari {{ $surveiList->total() }} survei
            </p>
            {{ $surveiList->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

@endsection
