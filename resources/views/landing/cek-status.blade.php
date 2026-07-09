@extends('layouts.public')

@section('title', 'Cek Status Bantuan')
@section('meta_description', 'Cek status pengajuan bantuan sosial berdasarkan NIK Anda. Lihat riwayat proses, jenis bantuan, dan unduh bukti penyaluran.')
@section('hide_navbar', true)
@section('hide_footer', true)

@push('styles')
<style>
    .cek-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background:
            linear-gradient(115deg, rgba(255,255,255,0.96) 0%, rgba(255,255,255,0.88) 48%, rgba(236,253,245,0.72) 100%),
            repeating-linear-gradient(135deg, rgba(8,145,178,0.07) 0 1px, transparent 1px 18px),
            linear-gradient(135deg, #f8fafc 0%, #eef6ff 50%, #ecfeff 100%);
        border-bottom: 1px solid var(--border);
        position: relative;
        overflow: hidden;
    }

    .cek-page::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(90deg, rgba(30,64,175,0.08), transparent 30%, transparent 70%, rgba(8,145,178,0.08)),
            linear-gradient(180deg, transparent 0%, rgba(255,255,255,0.72) 100%);
        pointer-events: none;
    }

    .cek-hero {
        min-height: 620px;
        display: flex;
        align-items: center;
        padding: 4.5rem 0;
        position: relative;
        background: transparent;
    }

    .cek-hero::before {
        content: '';
        position: absolute;
        width: 520px;
        height: 520px;
        background: linear-gradient(135deg, rgba(8,145,178,0.08), rgba(30,64,175,0.02));
        top: -210px;
        right: -150px;
        border-radius: 42px;
        transform: rotate(18deg);
        pointer-events: none;
    }

    .cek-hero::after {
        content: '';
        position: absolute;
        width: 320px;
        height: 320px;
        background: linear-gradient(135deg, rgba(30,64,175,0.08), rgba(8,145,178,0.02));
        bottom: -70px;
        left: -90px;
        border-radius: 38px;
        transform: rotate(-16deg);
        pointer-events: none;
    }

    .cek-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: var(--accent-light);
        border: 1.5px solid var(--accent);
        color: var(--accent);
        padding: .55rem 1.05rem;
        border-radius: 50px;
        font-size: .78rem;
        font-weight: 700;
        margin-bottom: 1.35rem;
        font-family: 'Poppins', sans-serif;
    }

    .cek-title {
        font-size: clamp(2.1rem, 4vw, 3.2rem);
        font-weight: 800;
        line-height: 1.15;
        color: var(--text-main);
        margin-bottom: 1rem;
        font-family: 'Poppins', sans-serif;
    }

    .cek-title span {
        color: var(--accent);
        display: block;
    }

    .cek-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
        line-height: 1.8;
        max-width: 560px;
        margin-bottom: 1.75rem;
    }

    .cek-mini-list {
        display: grid;
        gap: .8rem;
        max-width: 520px;
    }

    .cek-mini-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        color: var(--text-muted);
        font-size: .9rem;
        font-weight: 500;
    }

    .cek-mini-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: #eff6ff;
        color: var(--primary);
        border: 1px solid #dbeafe;
    }

    .search-card {
        background: linear-gradient(135deg, var(--primary) 0%, #1e3a8a 100%);
        border-radius: 28px;
        padding: 2.75rem 2.25rem;
        color: #fff;
        box-shadow: 0 24px 70px rgba(30, 64, 175, 0.22), 0 0 1px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 10;
        transform: rotateX(5deg) rotateY(-8deg) translateZ(0);
        transform-style: preserve-3d;
        transition: transform .35s ease, box-shadow .35s ease;
    }

    .search-card::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12), transparent 70%);
        top: -140px;
        right: -140px;
        border-radius: 50%;
        z-index: 1;
    }

    .search-card::after {
        content: '';
        position: absolute;
        inset: 1px;
        border-radius: 27px;
        background: linear-gradient(135deg, rgba(255,255,255,0.16), transparent 38%, rgba(255,255,255,0.06));
        pointer-events: none;
        z-index: 1;
    }

    .search-card-stage {
        position: relative;
        perspective: 1100px;
        padding: 1.25rem 0 1.25rem 1.25rem;
    }

    .search-card-stage::before,
    .search-card-stage::after {
        content: '';
        position: absolute;
        border-radius: 28px;
        pointer-events: none;
    }

    .search-card-stage::before {
        inset: 2.3rem -1.25rem .25rem 2.7rem;
        background: linear-gradient(135deg, rgba(8,145,178,0.42), rgba(30,64,175,0.08));
        transform: rotateX(5deg) rotateY(-8deg) translateZ(-42px);
        filter: blur(.2px);
        opacity: .8;
    }

    .search-card-stage::after {
        width: 72%;
        height: 36px;
        left: 20%;
        bottom: -.2rem;
        background: rgba(30,64,175,0.24);
        filter: blur(24px);
    }

    .search-card-stage:hover .search-card {
        transform: rotateX(2deg) rotateY(-3deg) translateY(-4px);
        box-shadow: 0 30px 80px rgba(30, 64, 175, 0.28), 0 0 1px rgba(0, 0, 0, 0.08);
    }

    .search-card > * {
        position: relative;
        z-index: 2;
    }

    .search-card-chip {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        background: rgba(255,255,255,0.14);
        border: 1px solid rgba(255,255,255,0.18);
        color: rgba(255,255,255,0.92);
        padding: .45rem .7rem;
        border-radius: 12px;
        font-size: .75rem;
        font-weight: 700;
        margin-bottom: 1.25rem;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.12);
    }

    .search-card h5 {
        font-weight: 700;
        color: #fff;
        font-size: 1.2rem;
        font-family: 'Poppins', sans-serif;
    }

    .search-card p {
        color: rgba(255, 255, 255, 0.84) !important;
    }

    .search-card .form-label {
        color: rgba(255, 255, 255, 0.96);
    }

    .search-card .form-text {
        color: rgba(255, 255, 255, 0.74);
    }

    .search-card .input-group {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.22);
        background: #fff;
    }

    .search-card .input-group-text { background: #fff !important; border: none !important; padding: 0 1rem !important; }
    .search-card .input-group-text i { color: var(--primary); font-size: 1.1rem; }
    .search-card input { border: none !important; padding: .9rem 1rem !important; font-size: .95rem; color: var(--text-main); }
    .search-card input::placeholder { color: #94a3b8; letter-spacing: 0; }
    .search-card input:focus { box-shadow: none; }
    .search-card button { background: #2563eb !important; color:#fff !important; font-weight:700; border:none !important; padding:.9rem 1.2rem !important; transition: all .3s; }
    .search-card button:hover { background: #1d4ed8 !important; transform: translateY(-2px); }

    .search-card .btn, .btn.btn-primary {
        background: var(--primary);
        color: #fff !important;
        border: none !important;
        padding: .75rem 1.4rem !important;
        border-radius: 12px;
        font-weight: 700;
        box-shadow: 0 6px 18px rgba(30,64,175,0.15);
    }

    .btn.btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }

    .cek-content {
        padding: 3rem 0 5rem;
        background: var(--bg-subtle);
        position: relative;
    }

    .info-card {
        background: #fff;
        border: 1.5px solid var(--border);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
    }

    .info-card-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1.5px solid var(--border);
        padding: 1.05rem 1.25rem;
        display: flex;
        gap: .75rem;
        align-items: center;
    }

    .info-card-header .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .info-card-header h5 {
        margin: 0;
        font-size: .95rem;
        font-weight: 800;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
    }

    .info-card-body { padding: 1.25rem; }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        padding: .65rem 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: .88rem;
    }

    .info-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-row-label {
        color: var(--text-muted);
        font-weight: 600;
        flex-shrink: 0;
        min-width: 140px;
    }

    .info-row-value {
        color: var(--text-main);
        font-weight: 700;
        text-align: right;
    }

    .status-banner {
        border-radius: 18px;
        padding: 1.35rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
    }

    .status-banner .banner-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }

    .bantuan-pill {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        background: #eff6ff;
        border: 1.5px solid #bfdbfe;
        color: #1d4ed8;
        padding: .5rem .85rem;
        border-radius: 50px;
        font-size: .78rem;
        font-weight: 800;
    }

    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary), #e2e8f0);
        border-radius: 2px;
    }

    .tl-item {
        position: relative;
        padding-bottom: 1.65rem;
    }

    .tl-item:last-child { padding-bottom: 0; }

    .tl-dot {
        position: absolute;
        left: -2rem;
        top: 2px;
        width: 22px;
        height: 22px;
        background: var(--primary);
        border: 3px solid #fff;
        border-radius: 50%;
        box-shadow: 0 0 0 2px var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .tl-item:last-child .tl-dot { background:#10b981; box-shadow:0 0 0 2px #10b981; }
    .tl-dot i { font-size:.62rem; color:#fff; }

    .tl-date {
        color: var(--text-muted);
        font-size: .74rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
        margin-bottom: .45rem;
    }

    .tl-content {
        background: var(--bg-subtle);
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: .9rem 1.1rem;
    }

    .tl-status {
        font-weight: 800;
        font-size: .86rem;
    }

    .tl-catatan {
        color: var(--text-muted);
        font-size: .83rem;
        line-height: 1.6;
        margin-bottom: 0;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }

    .empty-icon {
        width: 82px;
        height: 82px;
        background: #fee2e2;
        border-radius: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #dc2626;
        margin-bottom: 1.25rem;
    }

    .bukti-item { display:flex; align-items:center; gap:1rem; padding:1rem; background:var(--bg-subtle); border:1.5px solid var(--border); border-radius:12px }
    .bukti-icon { width:44px;height:44px;border-radius:10px;background:#dbeafe; display:flex; align-items:center; justify-content:center; color:var(--primary); }
    .bukti-name { font-weight: 800; font-size: .86rem; color: var(--text-main); }
    .bukti-type { color: var(--text-muted); font-size: .75rem; font-weight: 600; }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        color: var(--text-muted);
        text-decoration: none;
        font-size: .875rem;
        font-weight: 700;
        transition: color .2s;
    }

    .back-link:hover {
        color: var(--accent);
    }

    @media (max-width:768px) {
        .cek-page {
            align-items: flex-start;
        }

        .cek-hero {
            min-height: auto;
            padding: 2.5rem 0 2rem;
        }

        .search-card {
            padding: 1.5rem;
            border-radius: 20px;
            margin-top: 1.25rem;
            transform: none;
        }

        .search-card-stage {
            padding: 0;
        }

        .search-card-stage::before,
        .search-card-stage::after {
            display: none;
        }

        .cek-content {
            padding: 2rem 0 4rem;
        }

        .info-row {
            flex-direction: column;
            gap: .2rem;
        }

        .info-row-value {
            text-align: left;
        }
    }
</style>
@endpush

@section('content')

@php
use App\Helpers\StatusHelper;
@endphp

{{-- ─── Page Hero ─── --}}
<section class="cek-page">
    <div class="container">
        <div class="cek-hero">
            <div class="row align-items-center justify-content-center g-5 position-relative" style="z-index:1;">
                <div class="col-lg-5">
                    <nav aria-label="breadcrumb" class="mb-4">
                        <ol class="breadcrumb mb-0" style="font-size:.82rem;">
                            <li class="breadcrumb-item"><a href="{{ route('landing') }}" class="text-muted text-decoration-none">Beranda</a></li>
                            <li class="breadcrumb-item active" style="color:var(--accent);">Cek Status Bantuan</li>
                        </ol>
                    </nav>

                    <div class="cek-badge">
                        <i class="bi bi-shield-check"></i>
                        SiBansos
                    </div>
                    <h1 class="cek-title">
                        Cek Status
                        <span>Bantuan Sosial</span>
                    </h1>
                    <p class="cek-subtitle">
                        Masukkan NIK 16 digit untuk melihat perkembangan pengajuan, status verifikasi, dan informasi penyaluran bantuan.
                    </p>

                    <div class="cek-mini-list">
                        <div class="cek-mini-item">
                            <span class="cek-mini-icon"><i class="bi bi-search"></i></span>
                            <span>Pencarian cepat berdasarkan data kependudukan yang terdaftar.</span>
                        </div>
                        <div class="cek-mini-item">
                            <span class="cek-mini-icon"><i class="bi bi-clock-history"></i></span>
                            <span>Riwayat proses ditampilkan jelas setelah data ditemukan.</span>
                        </div>
                        <div class="cek-mini-item">
                            <span class="cek-mini-icon"><i class="bi bi-lock"></i></span>
                            <span>NIK ditampilkan secara aman pada hasil pencarian.</span>
                        </div>
                    </div>

                    @if(!isset($searched) || !$searched)
                    <div class="mt-4">
                        <a href="{{ route('landing') }}" class="back-link">
                            <i class="bi bi-arrow-left"></i>Kembali ke Beranda
                        </a>
                    </div>
                    @endif
                </div>

                <div class="col-lg-5">
                    <div class="search-card-stage">
                        <div class="search-card">
                            <div class="search-card-chip">
                                <i class="bi bi-shield-lock"></i>
                                Pencarian Aman
                            </div>
                            <h5 class="mb-1">Pencarian Status Bantuan</h5>
                            <p class="mb-4" style="font-size:.875rem;">Data akan ditampilkan sesuai dengan NIK yang terdaftar dalam sistem.</p>

                            <form action="{{ route('status') }}" method="GET" id="form-cek-status">
                                <div class="mb-3">
                                    <label for="nik" class="form-label fw-600 small" style="font-weight:700;">NIK (Nomor Induk Kependudukan)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-credit-card-2-front"></i>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control form-control-lg @error('nik') is-invalid @enderror"
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
                                    <div class="form-text mt-2">
                                        <i class="bi bi-info-circle me-1"></i>NIK harus terdiri dari 16 digit angka sesuai KTP.
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 fw-700" style="font-weight:700;border-radius:12px;font-size:.95rem;" id="btn-cek">
                                    <i class="bi bi-search me-2"></i>Cek Status
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(isset($searched) && $searched)
{{-- ─── Main Content ─── --}}
<section class="cek-content">
    <div class="container">

        {{-- ─── Hasil Pencarian ─── --}}

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

    </div>
</section>
@endif

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
