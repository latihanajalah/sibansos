@extends('layouts.app')

@section('content')

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Persetujuan Pengajuan</h2>
        <p class="text-muted mb-0">Kode Pengajuan: <strong>{{ $pengajuan->kode_pengajuan }}</strong></p>
    </div>
    <a href="{{ route('persetujuan.index') }}" class="btn btn-outline-secondary d-flex align-items-center gap-2">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<x-breadcrumb :items="['Persetujuan' => route('persetujuan.index'), 'Detail Persetujuan' => '#']" />

<div class="row g-4">
    {{-- Left Column: Data Penerima, Pengajuan, Jenis Bantuan, Hasil Survei, Foto --}}
    <div class="col-lg-8">
        {{-- Data Penerima & Pengajuan --}}
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-badge text-primary me-2"></i> Informasi Penerima & Pengajuan
            </h5>
            <div class="row g-3">
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Nama Lengkap</p>
                    <p class="fw-bold text-dark mb-0">{{ $pengajuan->penerima->nama ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">NIK</p>
                    <p class="fw-semibold text-dark mb-0">{{ $pengajuan->penerima->nik ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">No KK</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->penerima->no_kk ?? 'N/A' }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">No HP</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->penerima->no_hp ?? '-' }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Alamat</p>
                    <p class="fw-medium text-dark mb-0">
                        {{ $pengajuan->penerima->alamat ?? '' }}, RT {{ $pengajuan->penerima->rt }}/RW {{ $pengajuan->penerima->rw }},
                        Desa {{ $pengajuan->penerima->desa }}, Kec. {{ $pengajuan->penerima->kecamatan }}, Kab. {{ $pengajuan->penerima->kabupaten }}
                    </p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Tanggal Pengajuan</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</p>
                </div>
                <div class="col-sm-6">
                    <p class="text-muted small mb-1">Petugas Pengaju</p>
                    <p class="fw-medium text-dark mb-0">{{ $pengajuan->petugas->nama ?? 'N/A' }}</p>
                </div>
                <div class="col-12">
                    <p class="text-muted small mb-1">Jenis Bantuan Yang Diajukan</p>
                    <div>
                        @foreach($pengajuan->jenisBantuan as $bantuan)
                            <span class="badge bg-primary-light text-primary fs-6 px-3 py-2 me-1 mb-1">
                                <strong class="text-dark">{{ $bantuan->kode }}</strong> - {{ $bantuan->nama_bantuan }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Hasil Survei Lapangan --}}
        @if($pengajuan->survei)
            <div class="card card-saas border-0 p-4 mb-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                    <i class="bi bi-clipboard2-check text-primary me-2"></i> Hasil Survei Lapangan
                </h5>
                <div class="accordion border-0" id="accordionSurvei">
                    {{-- Kondisi Rumah & Bangunan --}}
                    <div class="accordion-item card border-light mb-2" style="border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold text-dark bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRumah">
                                Rumah & Bangunan
                            </button>
                        </h2>
                        <div id="collapseRumah" class="accordion-collapse collapse show" data-bs-parent="#accordionSurvei">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Status Rumah</p>
                                        <p class="fw-semibold text-dark mb-0">{{ $pengajuan->survei->status_rumah }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Kepemilikan</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->kepemilikan_rumah }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Jenis Lantai</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->jenis_lantai }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Jenis Dinding</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->jenis_dinding }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Jenis Atap</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->jenis_atap }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Penghuni & Ekonomi --}}
                    <div class="accordion-item card border-light mb-2" style="border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEkonomi">
                                Data Penghuni & Ekonomi
                            </button>
                        </h2>
                        <div id="collapseEkonomi" class="accordion-collapse collapse" data-bs-parent="#accordionSurvei">
                            <div class="accordion-body">
                                <div class="row g-3">
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Jumlah Kamar</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->jumlah_kamar }}</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Jumlah Penghuni</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->jumlah_penghuni }} orang</p>
                                    </div>
                                    <div class="col-sm-4">
                                        <p class="text-muted small mb-1">Tanggungan</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->jumlah_tanggungan }} orang</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-muted small mb-1">Pekerjaan</p>
                                        <p class="fw-medium text-dark mb-0">{{ $pengajuan->survei->pekerjaan }}</p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="text-muted small mb-1">Penghasilan / Bulan</p>
                                        <p class="fw-bold text-success mb-0">Rp {{ number_format($pengajuan->survei->penghasilan, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kepemilikan Aset --}}
                    <div class="accordion-item card border-light mb-2" style="border-radius: 8px; overflow: hidden;">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold text-dark bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAset">
                                Kepemilikan Aset
                            </button>
                        </h2>
                        <div id="collapseAset" class="accordion-collapse collapse" data-bs-parent="#accordionSurvei">
                            <div class="accordion-body">
                                <div class="row g-2">
                                    @php
                                        $asets = [
                                            ['Motor', $pengajuan->survei->punya_motor],
                                            ['Mobil', $pengajuan->survei->punya_mobil],
                                            ['Sawah', $pengajuan->survei->punya_sawah],
                                            ['Ternak', $pengajuan->survei->punya_ternak],
                                        ];
                                    @endphp
                                    @foreach($asets as [$label, $val])
                                        <div class="col-6 col-md-3">
                                            <div class="p-2 border rounded text-center {{ $val ? 'bg-success-subtle border-success text-success' : 'bg-light border-light text-muted' }}">
                                                <i class="bi {{ $val ? 'bi-check-circle-fill' : 'bi-x-circle' }} me-1"></i>
                                                {{ $label }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Catatan --}}
                    @if($pengajuan->survei->catatan)
                        <div class="accordion-item card border-light mb-2" style="border-radius: 8px; overflow: hidden;">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-dark bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCatatan">
                                    Catatan Tambahan
                                </button>
                            </h2>
                            <div id="collapseCatatan" class="accordion-collapse collapse" data-bs-parent="#accordionSurvei">
                                <div class="accordion-body bg-light rounded text-dark" style="white-space: pre-wrap;">{{ $pengajuan->survei->catatan }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Foto Survei --}}
                <h6 class="fw-bold text-dark mt-4 mb-3"><i class="bi bi-images text-muted me-2"></i> Foto Dokumentasi Lapangan</h6>
                @if($pengajuan->survei->foto->isEmpty())
                    <p class="text-muted small">Tidak ada foto diupload.</p>
                @else
                    <div class="row g-3">
                        @foreach($pengajuan->survei->foto as $foto)
                            <div class="col-sm-4 col-6">
                                <a href="{{ asset('storage/' . $foto->file) }}" target="_blank" class="d-block card border-light overflow-hidden shadow-sm">
                                    <img src="{{ asset('storage/' . $foto->file) }}" class="card-img-top object-fit-cover" style="height: 120px;" alt="{{ $foto->kategori }}">
                                    <div class="card-body p-2 text-center small text-muted fw-semibold">
                                        {{ $foto->kategori }}
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- Form Persetujuan Pimpinan --}}
        @if($pengajuan->status === 'menunggu_persetujuan' && auth()->user()->role === 'pimpinan')
            <div class="card card-saas border-0 p-4">
                <h5 class="fw-bold text-dark border-bottom pb-2 mb-4">
                    <i class="bi bi-check-circle text-primary me-2"></i> Keputusan Persetujuan Pimpinan
                </h5>

                <form action="{{ route('persetujuan.approve', $pengajuan) }}" method="POST" id="form-persetujuan">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold d-block mb-3">Keputusan Persetujuan <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border p-3 btn-select-card bg-light text-center" id="card-setujui" style="cursor: pointer;" onclick="selectDecision('setujui')">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="radio" name="keputusan" id="keputusan-setujui" value="setujui" required>
                                        <label class="form-check-label fw-bold text-success" for="keputusan-setujui">
                                            <i class="bi bi-check-circle me-1"></i> Setujui Pengajuan
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-2">Menyetujui bansos untuk disalurkan ke penerima.</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border p-3 btn-select-card bg-light text-center" id="card-tolak" style="cursor: pointer;" onclick="selectDecision('tolak')">
                                    <div class="form-check d-inline-block">
                                        <input class="form-check-input" type="radio" name="keputusan" id="keputusan-tolak" value="tolak" required>
                                        <label class="form-check-label fw-bold text-danger" for="keputusan-tolak">
                                            <i class="bi bi-x-circle me-1"></i> Tolak Pengajuan
                                        </label>
                                    </div>
                                    <small class="text-muted d-block mt-2">Menolak pengajuan bansos ini secara final.</small>
                                </div>
                            </div>
                        </div>
                        @error('keputusan')
                            <div class="text-danger small mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="catatan" class="form-label fw-bold" id="catatan-label">Catatan Persetujuan</label>
                        <textarea name="catatan" id="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4" placeholder="Tuliskan alasan persetujuan atau alasan penolakan..."></textarea>
                        <div class="invalid-feedback" id="catatan-feedback">Alasan penolakan wajib diisi jika Anda menolak pengajuan ini.</div>
                        @error('catatan')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('persetujuan.index') }}" class="btn btn-outline-secondary px-4">Batal</a>
                        <button type="submit" class="btn btn-primary px-5 fw-medium">
                            <i class="bi bi-send-check me-1"></i> Submit Keputusan
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="alert alert-light border border-light p-4 rounded-4 d-flex align-items-center gap-3">
                <i class="bi bi-info-circle-fill text-muted fs-4"></i>
                <div>
                    <span class="fw-semibold text-dark">Informasi</span><br>
                    <span class="text-muted small">Status pengajuan saat ini adalah <strong>{{ str_replace('_', ' ', $pengajuan->status) }}</strong>. Form persetujuan hanya dapat diakses oleh Pimpinan saat status pengajuan berstatus <strong>menunggu_persetujuan</strong>.</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Right Column: Dokumen Pendukung & Riwayat Status --}}
    <div class="col-lg-4">
        {{-- Dokumen Pendukung --}}
        <div class="card card-saas border-0 p-4 mb-4">
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-file-earmark-text text-primary me-2"></i> Dokumen Pendukung
            </h5>
            @if($pengajuan->dokumen->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-folder-x text-muted fs-1"></i>
                    <p class="text-muted small mt-2 mb-0">Tidak ada dokumen pendukung diunggah.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-2">
                    @foreach($pengajuan->dokumen as $dok)
                        <a href="{{ asset('storage/' . $dok->file) }}" target="_blank"
                           class="card p-2 border-light text-decoration-none d-flex flex-row align-items-center gap-2"
                           style="border-radius: 8px; transition: background 0.2s;"
                           onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='white'">
                            <i class="bi bi-file-earmark-pdf text-danger fs-4"></i>
                            <span class="text-dark small fw-medium text-truncate" style="max-width: 180px;">{{ $dok->nama_dokumen }}</span>
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
            @if($pengajuan->riwayatStatus->isEmpty())
                <div class="text-center py-4">
                    <i class="bi bi-journal-x text-muted fs-1"></i>
                    <p class="text-muted small mt-2 mb-0">Belum ada riwayat perubahan status.</p>
                </div>
            @else
                <div class="position-relative ps-3 border-start ms-2">
                    @foreach($pengajuan->riwayatStatus as $riwayat)
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

@push('js')
<script>
    function selectDecision(decision) {
        // Deselect all
        document.querySelectorAll('.btn-select-card').forEach(function (card) {
            card.classList.remove('border-success', 'border-danger', 'bg-white');
            card.classList.add('bg-light');
        });

        // Set checked input
        const input = document.getElementById('keputusan-' + decision);
        input.checked = true;

        // Style the active card
        const card = document.getElementById('card-' + decision);
        card.classList.remove('bg-light');
        card.classList.add('bg-white');
        if (decision === 'setujui') {
            card.classList.add('border-success');
            document.getElementById('catatan-label').innerHTML = 'Catatan Persetujuan <span class="fw-normal text-muted">(Opsional)</span>';
            document.getElementById('catatan').required = false;
        } else if (decision === 'tolak') {
            card.classList.add('border-danger');
            document.getElementById('catatan-label').innerHTML = 'Alasan Penolakan <span class="text-danger">*</span>';
            document.getElementById('catatan').required = true;
        }
    }

    // Client-side validation upon submitting
    document.getElementById('form-persetujuan')?.addEventListener('submit', function (e) {
        const decisions = document.getElementsByName('keputusan');
        let selectedValue = '';
        for (let i = 0; i < decisions.length; i++) {
            if (decisions[i].checked) {
                selectedValue = decisions[i].value;
                break;
            }
        }

        const catatan = document.getElementById('catatan').value.trim();

        if (selectedValue === 'tolak' && catatan === '') {
            e.preventDefault();
            document.getElementById('catatan').classList.add('is-invalid');
            document.getElementById('catatan-feedback').style.display = 'block';
            return false;
        } else {
            document.getElementById('catatan').classList.remove('is-invalid');
        }
    });
</script>
@endpush

@push('css')
<style>
    .bg-primary-light { background-color: rgba(var(--bs-primary-rgb), 0.1); }
</style>
@endpush
