@extends('layouts.public')

@section('title', 'Beranda')
@section('meta_description', 'Sistem Informasi Pengajuan Bantuan Sosial – cek status bantuan sosial Anda secara online, transparan dan akuntabel.')

@push('styles')
<style>
    /* ─── Hero ─────────────────────────────────────────────── */
    .hero-section {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563EB 45%, #7c3aed 100%);
        min-height: 90vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 70%);
        top: -150px;
        right: -100px;
        border-radius: 50%;
    }
    .hero-section::after {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,.05) 0%, transparent 70%);
        bottom: -100px;
        left: -50px;
        border-radius: 50%;
    }
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        padding: .4rem 1rem;
        border-radius: 50px;
        font-size: .8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(4px);
    }
    .hero-title {
        font-size: clamp(2rem, 5vw, 3.2rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 1.25rem;
    }
    .hero-subtitle {
        font-size: 1.1rem;
        color: rgba(255,255,255,.8);
        line-height: 1.7;
        margin-bottom: 2rem;
        max-width: 520px;
    }
    .hero-stat-card {
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        backdrop-filter: blur(8px);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        color: #fff;
        text-align: center;
    }
    .hero-stat-card .stat-number {
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: .2rem;
    }
    .hero-stat-card .stat-label {
        font-size: .75rem;
        color: rgba(255,255,255,.7);
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .hero-search-card {
        background: rgba(255,255,255,.1);
        border: 1px solid rgba(255,255,255,.2);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        padding: 2rem;
        color: #fff;
    }

    /* ─── Alur ─────────────────────────────────────────────── */
    .section-label {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--primary);
        margin-bottom: .5rem;
    }
    .section-title {
        font-size: clamp(1.6rem, 3vw, 2.2rem);
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: .75rem;
    }
    .section-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
        line-height: 1.7;
    }
    .alur-step {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .alur-icon {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
        transition: transform .3s;
    }
    .alur-icon:hover { transform: translateY(-4px); }
    .alur-number {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        background: var(--text-main);
        color: #fff;
        border-radius: 50%;
        font-size: .65rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .alur-connector {
        position: absolute;
        top: 36px;
        left: calc(50% + 36px);
        right: calc(-50% + 36px);
        height: 2px;
        background: linear-gradient(to right, var(--border), var(--primary));
        z-index: 0;
    }
    .alur-step:last-child .alur-connector { display: none; }
    .alur-step-title { font-weight: 700; font-size: .95rem; margin-bottom: .3rem; }
    .alur-step-desc { font-size: .8rem; color: var(--text-muted); line-height: 1.5; }

    /* ─── Jenis Bantuan ─────────────────────────────────────── */
    .bantuan-card {
        border: 1.5px solid var(--border);
        border-radius: 16px;
        padding: 1.75rem;
        transition: all .3s;
        background: #fff;
        height: 100%;
    }
    .bantuan-card:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 32px rgba(37,99,235,.1);
        transform: translateY(-4px);
    }
    .bantuan-icon {
        width: 52px;
        height: 52px;
        background: var(--primary-light);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: var(--primary);
        margin-bottom: 1rem;
    }
    .bantuan-kode {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--primary);
        background: var(--primary-light);
        padding: .2rem .6rem;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: .75rem;
    }
    .bantuan-card-title { font-weight: 700; font-size: 1.05rem; margin-bottom: .5rem; }
    .bantuan-card-desc { font-size: .875rem; color: var(--text-muted); line-height: 1.6; }

    /* ─── CTA Banner ─────────────────────────────────────────── */
    .cta-section {
        background: linear-gradient(135deg, #1e3a8a, #2563EB 60%, #7c3aed);
        border-radius: 24px;
        padding: 3.5rem 2rem;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .cta-section::before {
        content: '';
        position: absolute;
        width: 350px; height: 350px;
        background: radial-gradient(circle, rgba(255,255,255,.08), transparent 70%);
        top: -100px; right: -80px;
        border-radius: 50%;
    }
</style>
@endpush

@section('content')

{{-- ─── Hero ─── --}}
<section class="hero-section">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-shield-check-fill"></i>
                    Layanan Resmi Dinas Sosial
                </div>
                <h1 class="hero-title">
                    Sistem Informasi<br>
                    <span style="background:linear-gradient(90deg,#93c5fd,#c4b5fd);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Pengajuan Bantuan</span><br>
                    Sosial
                </h1>
                <p class="hero-subtitle">
                    Platform digital terintegrasi untuk transparansi penyaluran bantuan sosial kepada masyarakat. Mudah diakses, cepat, dan dapat dipantau secara real-time.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('cek-status') }}" class="btn-primary-lp">
                        <i class="bi bi-search"></i>
                        Cek Status Bantuan
                    </a>
                    <a href="#alur" class="btn-secondary-lp">
                        <i class="bi bi-play-circle"></i>
                        Lihat Alur
                    </a>
                </div>
                <div class="row g-3 mt-4">
                    <div class="col-4">
                        <div class="hero-stat-card">
                            <div class="stat-number"><i class="bi bi-people-fill" style="font-size:1.5rem;"></i></div>
                            <div class="stat-label">Penerima</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="hero-stat-card">
                            <div class="stat-number"><i class="bi bi-file-earmark-check-fill" style="font-size:1.5rem;"></i></div>
                            <div class="stat-label">Pengajuan</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="hero-stat-card">
                            <div class="stat-number"><i class="bi bi-truck" style="font-size:1.5rem;"></i></div>
                            <div class="stat-label">Disalurkan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-search-card">
                    <h5 class="fw-700 mb-1" style="font-weight:700;font-size:1.1rem;">Cek Status Bantuan</h5>
                    <p class="mb-4" style="font-size:.875rem;color:rgba(255,255,255,.75);">Masukkan NIK (16 digit) untuk melihat status pengajuan bantuan Anda.</p>
                    <form action="{{ route('status') }}" method="GET">
                        <div class="input-group" style="border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.15);">
                            <span class="input-group-text bg-white border-0 ps-3">
                                <i class="bi bi-credit-card-2-front" style="color:#2563EB;font-size:1.1rem;"></i>
                            </span>
                            <input
                                type="text"
                                name="nik"
                                id="nik-hero"
                                class="form-control border-0 py-3"
                                placeholder="Masukkan NIK 16 digit..."
                                maxlength="16"
                                inputmode="numeric"
                                value="{{ request('nik') }}"
                                style="font-size:.95rem;"
                            >
                            <button type="submit" class="btn px-4 fw-700 border-0" style="background:linear-gradient(135deg,#2563EB,#7c3aed);color:#fff;font-weight:700;">
                                <i class="bi bi-search me-1"></i>
                                <span class="d-none d-sm-inline">Cari</span>
                            </button>
                        </div>
                        <p class="mt-2 mb-0" style="font-size:.75rem;color:rgba(255,255,255,.6);">
                            <i class="bi bi-lock-fill me-1"></i>Data Anda aman. NIK hanya digunakan untuk pencarian status.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Alur Proses ─── --}}
<section id="alur" class="py-6" style="padding:5rem 0;background:var(--bg-subtle);">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-label">Alur Proses</p>
            <h2 class="section-title">Bagaimana Bantuan Disalurkan?</h2>
            <p class="section-subtitle mx-auto" style="max-width:520px;">Setiap pengajuan melewati tahapan yang ketat untuk memastikan penyaluran tepat sasaran.</p>
        </div>
        <div class="row g-4 row-cols-2 row-cols-md-5 justify-content-center">
            @php
            $steps = [
                ['icon'=>'bi-file-earmark-person-fill','color'=>'#dbeafe','icolor'=>'#2563eb','title'=>'Pengajuan','desc'=>'Petugas mengajukan calon penerima'],
                ['icon'=>'bi-clipboard2-check-fill','color'=>'#d1fae5','icolor'=>'#059669','title'=>'Survei','desc'=>'Verifikasi kondisi di lapangan'],
                ['icon'=>'bi-shield-check-fill','color'=>'#fef3c7','icolor'=>'#d97706','title'=>'Verifikasi','desc'=>'Admin meninjau hasil survei'],
                ['icon'=>'bi-person-check-fill','color'=>'#ede9fe','icolor'=>'#7c3aed','title'=>'Persetujuan','desc'=>'Pimpinan memberi keputusan akhir'],
                ['icon'=>'bi-truck','color'=>'#dcfce7','icolor'=>'#16a34a','title'=>'Penyaluran','desc'=>'Bantuan diterima penerima'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="col">
                <div class="alur-step">
                    @if($i < count($steps) - 1)
                    <div class="alur-connector d-none d-md-block"></div>
                    @endif
                    <div class="alur-icon" style="background:{{ $step['color'] }};color:{{ $step['icolor'] }};">
                        <i class="{{ $step['icon'] }}"></i>
                        <span class="alur-number">{{ $i + 1 }}</span>
                    </div>
                    <p class="alur-step-title">{{ $step['title'] }}</p>
                    <p class="alur-step-desc">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ─── Jenis Bantuan ─── --}}
@if($jenisBantuan->isNotEmpty())
<section id="bantuan" style="padding:5rem 0;">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-label">Program Bantuan</p>
            <h2 class="section-title">Jenis Bantuan Tersedia</h2>
            <p class="section-subtitle mx-auto" style="max-width:480px;">Program bantuan sosial yang aktif dan dapat diajukan oleh masyarakat yang memenuhi syarat.</p>
        </div>
        @php
        $bantuanIcons = ['bi-basket3-fill','bi-house-fill','bi-mortarboard-fill','bi-heart-pulse-fill','bi-lightning-charge-fill','bi-droplet-fill'];
        @endphp
        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
            @foreach($jenisBantuan as $i => $jb)
            <div class="col">
                <div class="bantuan-card">
                    <div class="bantuan-icon">
                        <i class="{{ $bantuanIcons[$i % count($bantuanIcons)] }}"></i>
                    </div>
                    <span class="bantuan-kode">{{ $jb->kode }}</span>
                    <h3 class="bantuan-card-title">{{ $jb->nama_bantuan }}</h3>
                    @if($jb->deskripsi)
                    <p class="bantuan-card-desc mb-0">{{ Str::limit($jb->deskripsi, 100) }}</p>
                    @else
                    <p class="bantuan-card-desc mb-0">Program bantuan sosial resmi dari Dinas Sosial.</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─── CTA ─── --}}
<section style="padding:3rem 0 5rem;">
    <div class="container">
        <div class="cta-section">
            <p class="section-label" style="color:rgba(255,255,255,.7);">Mulai Sekarang</p>
            <h2 style="font-weight:800;font-size:2rem;color:#fff;margin-bottom:.75rem;">Ingin Tahu Status Bantuan Anda?</h2>
            <p style="color:rgba(255,255,255,.8);margin-bottom:2rem;font-size:1rem;">Cek status pengajuan bantuan sosial Anda hanya dengan memasukkan NIK. Gratis dan tanpa pendaftaran.</p>
            <a href="{{ route('cek-status') }}" class="btn-primary-lp" style="background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.35);backdrop-filter:blur(4px);box-shadow:none;">
                <i class="bi bi-search"></i>
                Cek Status Bantuan Sekarang
            </a>
        </div>
    </div>
</section>

@endsection
