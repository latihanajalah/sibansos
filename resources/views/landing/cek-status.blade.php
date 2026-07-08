@extends('layouts.public')

@section('title', 'Cek Status Bantuan')
@section('meta_description', 'Cek status pengajuan bantuan sosial berdasarkan NIK Anda. Lihat riwayat proses, jenis bantuan, dan unduh bukti penyaluran.')

@push('styles')
<style>
    /* ─── Page Hero ─────────────────────────────────── */
    .cek-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563EB 55%, #7c3aed 100%);
        padding: 4rem 0 3rem;
        position: relative;
        overflow: hidden;
    }
    .cek-hero::before {
        content: '';
        position: absolute;
        width: 500px; height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,.06), transparent 70%);
        top: -180px; right: -100px;
        border-radius: 50%;
    }

    /* ─── Search Card ────────────────────────────────── */
    .search-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,.12);
        padding: 2.5rem;
        margin-top: -2rem;
        position: relative;
        z-index: 10;
    }

    /* ─── Status Badge ───────────────────────────────── */
    .status-badge-lg {
        font-size: .85rem;
        font-weight: 700;
        padding: .5rem 1.2rem;
        border-radius: 50px;
        letter-spacing: .03em;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
    }

    /* ─── Info Cards ─────────────────────────────────── */
    .info-card {
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
    }
    .info-card-header {
        background: var(--bg-subtle);
        border-bottom: 1.5px solid var(--border);
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: .65rem;
    }
    .info-card-header .card-icon {
        width: 36px; height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }
    .info-card-header h5 {
        margin: 0;
        font-weight: 700;
        font-size: .95rem;
    }
    .info-card-body { padding: 1.5rem; }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        padding: .55rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: .875rem;
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-row-label {
        color: var(--text-muted);
        font-weight: 500;
        flex-shrink: 0;
        min-width: 140px;
    }
    .info-row-value {
        font-weight: 600;
        color: var(--text-main);
        text-align: right;
    }

    /* ─── Timeline ───────────────────────────────────── */
    .timeline { position: relative; padding-left: 2rem; }
    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #2563EB, #e2e8f0);
        border-radius: 2px;
    }
    .tl-item {
        position: relative;
        padding-bottom: 1.75rem;
    }
    .tl-item:last-child { padding-bottom: 0; }
    .tl-dot {
        position: absolute;
        left: -2rem;
        top: 2px;
        width: 20px; height: 20px;
        background: #2563EB;
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 2px #2563EB;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .tl-item:last-child .tl-dot {
        background: #10b981;
        box-shadow: 0 0 0 2px #10b981;
    }
    .tl-dot i { font-size: .55rem; color: #fff; }
    .tl-date {
        font-size: .72rem;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: .3rem;
    }
    .tl-content {
        background: var(--bg-subtle);
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: .9rem 1.1rem;
    }
    .tl-status {
        font-weight: 700;
        font-size: .875rem;
        margin-bottom: .2rem;
    }
    .tl-catatan {
        font-size: .8rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0;
    }

    /* ─── Bantuan Pill ───────────────────────────────── */
    .bantuan-pill {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: #eff6ff;
        border: 1.5px solid #bfdbfe;
        color: #1d4ed8;
        padding: .5rem 1rem;
        border-radius: 50px;
        font-size: .8rem;
        font-weight: 700;
    }

    /* ─── Bukti Download ─────────────────────────────── */
    .bukti-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: var(--bg-subtle);
        border: 1.5px solid var(--border);
        border-radius: 12px;
        transition: all .2s;
    }
    .bukti-item:hover {
        border-color: var(--primary);
        background: var(--primary-light);
    }
    .bukti-icon {
        width: 42px; height: 42px;
        background: #dbeafe;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: #2563EB;
        flex-shrink: 0;
    }
    .bukti-name { font-weight: 600; font-size: .875rem; margin-bottom: .1rem; }
    .bukti-type { font-size: .75rem; color: var(--text-muted); }

    /* ─── Empty State ────────────────────────────────── */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-icon {
        width: 80px; height: 80px;
        background: #fee2e2;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #ef4444;
        margin-bottom: 1.5rem;
    }

    /* ─── Alert Banner ───────────────────────────────── */
    .status-banner {
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .status-banner .banner-icon {
        width: 52px; height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')

@php
use App\Helpers\StatusHelper;
@endphp

{{-- ─── Page Hero ─── --}}
<section class="cek-hero">
    <div class="container position-relative" style="z-index:1;">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb mb-0" style="font-size:.8rem;">
                <li class="breadcrumb-item"><a href="{{ route('landing') }}" class="text-white-50 text-decoration-none">Beranda</a></li>
                <li class="breadcrumb-item active text-white">Cek Status Bantuan</li>
            </ol>
        </nav>
        <h1 class="text-white fw-800 mb-1" style="font-weight:800;font-size:1.8rem;">Cek Status Bantuan</h1>
        <p class="text-white-50 mb-0" style="font-size:.95rem;">Masukkan NIK 16 digit untuk melihat status pengajuan bantuan sosial Anda.</p>
    </div>
</section>

{{-- ─── Main Content ─── --}}
<section style="padding:2rem 0 5rem;background:var(--bg-subtle);">
    <div class="container">

        {{-- Search Card --}}
        <div class="search-card mb-4">
            <h5 class="fw-700 mb-1" style="font-weight:700;">Pencarian Status Bantuan</h5>
            <p class="text-muted mb-4" style="font-size:.875rem;">Data akan ditampilkan sesuai dengan NIK yang terdaftar dalam sistem.</p>

            <form action="{{ route('status') }}" method="GET" id="form-cek-status">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8 col-lg-9">
                        <label for="nik" class="form-label fw-600 small" style="font-weight:600;">NIK (Nomor Induk Kependudukan)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-credit-card-2-front text-primary"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control form-control-lg py-3 @error('nik') is-invalid @enderror"
                                id="nik"
                                name="nik"
                                placeholder="Contoh: 3275XXXXXXXXXXXX"
                                value="{{ old('nik', $nik ?? '') }}"
                                maxlength="16"
                                inputmode="numeric"
                                autocomplete="off"
                                style="font-size:1rem;letter-spacing:.08em;"
                            >
                            @error('nik')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text mt-1">
                            <i class="bi bi-info-circle me-1"></i>NIK harus terdiri dari 16 digit angka sesuai KTP.
                        </div>
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-700" style="font-weight:700;border-radius:10px;font-size:.95rem;" id="btn-cek">
                            <i class="bi bi-search me-2"></i>Cek Status
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- ─── Hasil Pencarian ─── --}}
        @if(isset($searched) && $searched)

            {{-- TIDAK DITEMUKAN --}}
            @if(is_null($penerima) || is_null($pengajuan ?? null))
            <div class="info-card">
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-person-x-fill"></i>
                    </div>
                    <h4 class="fw-700 mb-2" style="font-weight:700;">Data Tidak Ditemukan</h4>
                    <p class="text-muted mb-4" style="max-width:420px;margin:0 auto .5rem;">
                        @if(is_null($penerima))
                            Data pengajuan tidak ditemukan. Pastikan NIK yang dimasukkan benar.
                        @else
                            NIK <strong>{{ str_repeat('*', 12) . substr($nik, -4) }}</strong> ditemukan, namun belum memiliki data pengajuan bantuan.
                        @endif
                    </p>
                    <div class="d-flex gap-2 justify-content-center flex-wrap mt-3">
                        <a href="{{ route('cek-status') }}" class="btn btn-primary" style="border-radius:8px;">
                            <i class="bi bi-arrow-left me-1"></i>Coba NIK Lain
                        </a>
                    </div>
                </div>
            </div>

            {{-- DITEMUKAN --}}
            @else
            @php
                $statusInfo  = StatusHelper::label($pengajuan->status);
                $nikMasked   = str_repeat('*', 12) . substr($penerima->nik, -4);
                $isSelesai   = $pengajuan->status === 'selesai';
                $isDitolak   = str_starts_with($pengajuan->status, 'ditolak');
                $penyaluran  = $pengajuan->penyaluran->first();
                $buktiList   = $pengajuan->dokumen;
            @endphp

            {{-- Status Banner --}}
            <div class="mb-4">
                @if($isSelesai)
                <div class="status-banner" style="background:#f0fdf4;border:1.5px solid #bbf7d0;">
                    <div class="banner-icon" style="background:#dcfce7;color:#16a34a;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="font-weight:700;color:#15803d;">Bantuan Telah Disalurkan</div>
                        <div style="font-size:.85rem;color:#166534;">Selamat! Pengajuan bantuan sosial Anda telah berhasil diselesaikan.</div>
                    </div>
                </div>
                @elseif($isDitolak)
                <div class="status-banner" style="background:#fff5f5;border:1.5px solid #fecaca;">
                    <div class="banner-icon" style="background:#fee2e2;color:#dc2626;">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="font-weight:700;color:#b91c1c;">Pengajuan Ditolak</div>
                        <div style="font-size:.85rem;color:#7f1d1d;">Mohon maaf, pengajuan bantuan sosial Anda tidak dapat diproses. Silakan hubungi petugas setempat.</div>
                    </div>
                </div>
                @else
                <div class="status-banner" style="background:#eff6ff;border:1.5px solid #bfdbfe;">
                    <div class="banner-icon" style="background:#dbeafe;color:#2563eb;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div>
                        <div class="fw-700" style="font-weight:700;color:#1d4ed8;">Pengajuan Sedang Diproses</div>
                        <div style="font-size:.85rem;color:#1e40af;">Pengajuan Anda sedang dalam tahap: <strong>{{ $statusInfo[0] }}</strong>. Pantau terus perkembangannya.</div>
                    </div>
                </div>
                @endif
            </div>

            <div class="row g-4">
                {{-- Kolom Kiri --}}
                <div class="col-lg-5">

                    {{-- Data Penerima --}}
                    <div class="info-card mb-4">
                        <div class="info-card-header">
                            <div class="card-icon" style="background:#dbeafe;color:#2563eb;">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                            <h5>Data Penerima</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="info-row">
                                <span class="info-row-label">Nama</span>
                                <span class="info-row-value">{{ $penerima->nama }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">NIK</span>
                                <span class="info-row-value" style="font-family:monospace;letter-spacing:.06em;">{{ $nikMasked }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Desa / Kelurahan</span>
                                <span class="info-row-value">{{ $penerima->desa }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Kecamatan</span>
                                <span class="info-row-value">{{ $penerima->kecamatan }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Kabupaten / Kota</span>
                                <span class="info-row-value">{{ $penerima->kabupaten }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Data Pengajuan --}}
                    <div class="info-card mb-4">
                        <div class="info-card-header">
                            <div class="card-icon" style="background:#f3e8ff;color:#7c3aed;">
                                <i class="bi bi-file-earmark-text-fill"></i>
                            </div>
                            <h5>Data Pengajuan</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="info-row">
                                <span class="info-row-label">Kode Pengajuan</span>
                                <span class="info-row-value" style="font-family:monospace;font-size:.8rem;">{{ $pengajuan->kode_pengajuan }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Tanggal Pengajuan</span>
                                <span class="info-row-value">{{ $pengajuan->tanggal_pengajuan->format('d M Y') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Petugas Pengaju</span>
                                <span class="info-row-value">{{ $pengajuan->petugas->nama ?? '-' }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-row-label">Status Saat Ini</span>
                                <span class="info-row-value">
                                    <span class="badge rounded-pill bg-{{ $statusInfo[1] }} status-badge-lg px-3 py-2" style="font-size:.75rem;">
                                        <i class="bi {{ $statusInfo[2] }} me-1"></i>{{ $statusInfo[0] }}
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Jenis Bantuan --}}
                    <div class="info-card mb-4">
                        <div class="info-card-header">
                            <div class="card-icon" style="background:#d1fae5;color:#059669;">
                                <i class="bi bi-gift-fill"></i>
                            </div>
                            <h5>Jenis Bantuan Diajukan</h5>
                        </div>
                        <div class="info-card-body">
                            @if($pengajuan->jenisBantuan->isNotEmpty())
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($pengajuan->jenisBantuan as $jb)
                                <span class="bantuan-pill">
                                    <i class="bi bi-tag-fill"></i>
                                    {{ $jb->nama_bantuan }}
                                </span>
                                @endforeach
                            </div>
                            @else
                            <p class="text-muted mb-0 small">Belum ada jenis bantuan yang tercatat.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Info Penyaluran (jika selesai) --}}
                    @if($isSelesai && $penyaluran)
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="card-icon" style="background:#dcfce7;color:#16a34a;">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h5>Informasi Penyaluran</h5>
                        </div>
                        <div class="info-card-body">
                            <div class="info-row">
                                <span class="info-row-label">Tanggal Penyaluran</span>
                                <span class="info-row-value text-success fw-700" style="font-weight:700;">
                                    {{ \Carbon\Carbon::parse($penyaluran->tanggal)->format('d M Y') }}
                                </span>
                            </div>
                            @if($penyaluran->catatan)
                            <div class="info-row">
                                <span class="info-row-label">Catatan</span>
                                <span class="info-row-value">{{ $penyaluran->catatan }}</span>
                            </div>
                            @endif

                            {{-- Bukti Penyaluran --}}
                            @if($buktiList->isNotEmpty())
                            <div class="mt-3 pt-3" style="border-top:1.5px solid var(--border);">
                                <p class="fw-700 small mb-3" style="font-weight:700;color:var(--text-main);">
                                    <i class="bi bi-paperclip me-1"></i>Bukti Penyaluran
                                </p>
                                <div class="d-flex flex-column gap-2">
                                    @foreach($buktiList as $idx => $bukti)
                                    @php
                                        $ext = strtolower(pathinfo($bukti->file, PATHINFO_EXTENSION));
                                        $icon = in_array($ext, ['jpg','jpeg','png','webp']) ? 'bi-image-fill' : 'bi-file-earmark-pdf-fill';
                                        $iconColor = in_array($ext, ['jpg','jpeg','png','webp']) ? '#2563eb' : '#dc2626';
                                        $filename = 'Bukti Penyaluran ' . ($idx + 1) . '.' . $ext;
                                    @endphp
                                    <div class="bukti-item">
                                        <div class="bukti-icon" style="color:{{ $iconColor }};">
                                            <i class="bi {{ $icon }}"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="bukti-name">{{ $filename }}</div>
                                            <div class="bukti-type">{{ strtoupper($ext) }}</div>
                                        </div>
                                        <a
                                            href="{{ Storage::url($bukti->file) }}"
                                            target="_blank"
                                            class="btn btn-sm btn-outline-primary"
                                            style="border-radius:8px;font-weight:600;font-size:.8rem;"
                                            download
                                            id="btn-download-bukti-{{ $idx + 1 }}"
                                        >
                                            <i class="bi bi-download me-1"></i>Download
                                        </a>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <div class="mt-3 pt-3" style="border-top:1.5px solid var(--border);">
                                <p class="text-muted small mb-0"><i class="bi bi-info-circle me-1"></i>Bukti penyaluran belum tersedia.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Kolom Kanan - Timeline --}}
                <div class="col-lg-7">
                    <div class="info-card">
                        <div class="info-card-header">
                            <div class="card-icon" style="background:#fef3c7;color:#d97706;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <h5>Riwayat Proses Pengajuan</h5>
                        </div>
                        <div class="info-card-body">
                            @if($pengajuan->riwayatStatus->isNotEmpty())
                            <div class="timeline">
                                @foreach($pengajuan->riwayatStatus as $riwayat)
                                @php $ri = StatusHelper::label($riwayat->status); @endphp
                                <div class="tl-item">
                                    <div class="tl-dot">
                                        <i class="bi bi-check-lg" style="font-size:.7rem;color:#fff;"></i>
                                    </div>
                                    <div class="tl-date">
                                        {{ \Carbon\Carbon::parse($riwayat->created_at)->format('d M Y, H:i') }}
                                        @if($riwayat->user)
                                        <span class="ms-2" style="font-weight:400;text-transform:none;letter-spacing:0;color:#94a3b8;">
                                            · {{ $riwayat->user->nama ?? '' }}
                                        </span>
                                        @endif
                                    </div>
                                    <div class="tl-content">
                                        <div class="tl-status">
                                            <span class="badge rounded-pill bg-{{ $ri[1] }}" style="font-size:.7rem;padding:.3rem .75rem;">
                                                <i class="bi {{ $ri[2] }} me-1"></i>{{ $ri[0] }}
                                            </span>
                                        </div>
                                        @if($riwayat->catatan)
                                        <p class="tl-catatan mt-2">{{ $riwayat->catatan }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bi bi-clock text-muted" style="font-size:2rem;"></i>
                                <p class="text-muted mt-2 mb-0 small">Belum ada riwayat status tercatat.</p>
                            </div>
                            @endif

                            {{-- Accordion - FAQ / Arti Status --}}
                            <div class="mt-4 pt-4" style="border-top:1.5px solid var(--border);">
                                <p class="fw-700 small mb-3" style="font-weight:700;">
                                    <i class="bi bi-question-circle me-1 text-primary"></i>
                                    Apa Arti Status Pengajuan?
                                </p>
                                <div class="accordion accordion-flush" id="faqStatus">
                                    @php
                                    $faqs = [
                                        ['Menunggu Survei',      'warning', 'Pengajuan telah diterima dan menunggu kunjungan survei lapangan oleh petugas.'],
                                        ['Menunggu Verifikasi',  'info',    'Survei telah selesai dan data sedang diperiksa oleh Admin.'],
                                        ['Revisi Survei',        'warning', 'Admin meminta petugas untuk melengkapi atau memperbaiki data survei.'],
                                        ['Menunggu Persetujuan', 'primary', 'Verifikasi Admin selesai. Menunggu keputusan akhir dari Pimpinan.'],
                                        ['Siap Disalurkan',      'success', 'Pengajuan disetujui Pimpinan. Bantuan siap disalurkan kepada penerima.'],
                                        ['Selesai',              'success', 'Bantuan telah resmi disalurkan kepada penerima.'],
                                        ['Ditolak',              'danger',  'Pengajuan tidak memenuhi syarat dan tidak dapat diproses.'],
                                    ];
                                    @endphp
                                    @foreach($faqs as $i => $faq)
                                    <div class="accordion-item border-0" style="border-bottom:1px solid var(--border)!important;border-radius:0!important;">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2 px-0" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}" style="font-size:.8rem;font-weight:600;background:transparent;box-shadow:none;color:var(--text-main);">
                                                <span class="badge bg-{{ $faq[1] }} me-2" style="font-size:.65rem;">{{ $faq[0] }}</span>
                                            </button>
                                        </h2>
                                        <div id="faq{{ $i }}" class="accordion-collapse collapse" data-bs-parent="#faqStatus">
                                            <div class="accordion-body px-0 pt-0 pb-3" style="font-size:.8rem;color:var(--text-muted);line-height:1.6;">
                                                {{ $faq[2] }}
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        @endif

        {{-- Back to landing jika belum search --}}
        @if(!isset($searched) || !$searched)
        <div class="text-center mt-4">
            <a href="{{ route('landing') }}" class="text-muted text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Beranda
            </a>
        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
    // Hanya izinkan input angka pada field NIK
    document.getElementById('nik')?.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 16);
        // Counter digit
        const len = this.value.length;
        const hint = document.querySelector('.form-text');
        if (hint) {
            if (len === 16) {
                hint.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i>NIK valid – siap dicari.';
            } else if (len > 0) {
                hint.innerHTML = `<i class="bi bi-info-circle me-1"></i>${len}/16 digit dimasukkan.`;
            } else {
                hint.innerHTML = '<i class="bi bi-info-circle me-1"></i>NIK harus terdiri dari 16 digit angka sesuai KTP.';
            }
        }
    });

    // Loading state button
    document.getElementById('form-cek-status')?.addEventListener('submit', function() {
        const btn = document.getElementById('btn-cek');
        if (btn) {
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mencari...';
            btn.disabled = true;
        }
    });
</script>
@endpush
