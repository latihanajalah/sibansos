@extends('layouts.public')

@section('title', 'Beranda')
@section('meta_description', 'Sistem Informasi Pengajuan Bantuan Sosial – cek status bantuan sosial Anda secara online, transparan dan akuntabel.')

@push('styles')
<style>
    /* ─── Hero ─────────────────────────────────────────────── */
    .hero-section {
        background:
            linear-gradient(115deg, rgba(255,255,255,0.96) 0%, rgba(255,255,255,0.9) 54%, rgba(236,253,245,0.62) 100%),
            repeating-linear-gradient(135deg, rgba(8,145,178,0.05) 0 1px, transparent 1px 20px),
            #ffffff;
        min-height: 95vh;
        display: flex;
        align-items: center;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid var(--border);
    }

    .hero-section::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(8, 145, 178, 0.06) 0%, transparent 70%);
        top: -150px;
        right: -100px;
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(30, 64, 175, 0.05) 0%, transparent 70%);
        bottom: 50px;
        left: -80px;
        border-radius: 50%;
        pointer-events: none;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: var(--accent-light);
        border: 1.5px solid var(--accent);
        color: var(--accent);
        padding: .6rem 1.2rem;
        border-radius: 50px;
        font-size: .8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-family: 'Poppins', sans-serif;
    }

    .hero-title {
        font-size: clamp(2.2rem, 5vw, 3.5rem);
        font-weight: 800;
        color: var(--text-main);
        line-height: 1.15;
        margin-bottom: 1.25rem;
        font-family: 'Poppins', sans-serif;
    }

    .hero-title .accent-text {
        color: var(--accent);
        display: block;
    }

    .hero-subtitle {
        font-size: 1.05rem;
        color: var(--text-muted);
        line-height: 1.8;
        margin-bottom: 2rem;
        max-width: 560px;
    }

    .hero-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 3rem;
    }

    .hero-stat-group {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-top: 3rem;
    }

    .hero-stat-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid var(--border);
        border-radius: 18px;
        padding: 1.75rem 1.5rem;
        color: var(--text-main);
        text-align: center;
        transition: all .3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .hero-stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(8, 145, 178, 0.08), transparent 70%);
        border-radius: 50%;
        z-index: -1;
    }

    .hero-stat-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, transparent 0%, rgba(8, 145, 178, 0.04) 100%);
        opacity: 0;
        transition: opacity .3s;
        z-index: 1;
    }

    .hero-stat-card:hover {
        transform: translateY(-12px) scale(1.02);
        border-color: var(--accent);
        background: linear-gradient(135deg, rgba(8, 145, 178, 0.04) 0%, rgba(8, 145, 178, 0.01) 100%);
        box-shadow: 0 16px 32px rgba(8, 145, 178, 0.15);
    }

    .hero-stat-card:hover::after {
        opacity: 1;
    }

    .hero-stat-card .stat-number {
        font-size: 2rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: .5rem;
        color: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        height: 48px;
        position: relative;
        z-index: 2;
    }

    .hero-stat-card .stat-label {
        font-size: .8rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        font-weight: 700;
        position: relative;
        z-index: 2;
    }

    .hero-right-card {
        position: relative;
        z-index: 2;
        perspective: 1100px;
    }

    .hero-right-card::before,
    .hero-right-card::after {
        content: '';
        position: absolute;
        border-radius: 28px;
        pointer-events: none;
    }

    .hero-right-card::before {
        inset: 2.2rem -1rem .3rem 2.6rem;
        background: linear-gradient(135deg, rgba(8,145,178,0.34), rgba(30,64,175,0.08));
        transform: rotateX(5deg) rotateY(-7deg) translateZ(-40px);
        opacity: .75;
    }

    .hero-right-card::after {
        width: 68%;
        height: 38px;
        left: 18%;
        bottom: -.4rem;
        background: rgba(30,64,175,0.22);
        filter: blur(24px);
    }

    .hero-search-card {
        background: linear-gradient(135deg, var(--primary) 0%, #1e3a8a 100%);
        border-radius: 28px;
        padding: 2.75rem 2.25rem;
        color: #fff;
        box-shadow: 0 20px 60px rgba(30, 64, 175, 0.2), 0 0 1px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transform: rotateX(5deg) rotateY(-7deg) translateZ(0);
        transform-style: preserve-3d;
        transition: transform .35s ease, box-shadow .35s ease;
    }

    .hero-search-card::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.12), transparent 70%);
        top: -120px;
        right: -100px;
        border-radius: 50%;
        z-index: 1;
        animation: float 6s ease-in-out infinite;
    }

    .hero-search-card::after {
        content: '';
        position: absolute;
        inset: 1px;
        border-radius: 27px;
        background: linear-gradient(135deg, rgba(255,255,255,0.16), transparent 42%, rgba(255,255,255,0.06));
        pointer-events: none;
        z-index: 1;
    }

    .hero-right-card:hover .hero-search-card {
        transform: rotateX(2deg) rotateY(-3deg) translateY(-5px);
        box-shadow: 0 30px 80px rgba(30, 64, 175, 0.28), 0 0 1px rgba(0, 0, 0, 0.08);
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) translateX(0px); }
        50% { transform: translateY(-20px) translateX(10px); }
    }

    .hero-search-card h5 {
        font-weight: 700;
        font-size: 1.2rem;
        margin-bottom: .5rem;
        position: relative;
        z-index: 2;
        font-family: 'Poppins', sans-serif;
    }

    .hero-search-card p {
        font-size: .875rem;
        color: rgba(255,255,255,.85);
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
    }

    .hero-search-card form {
        position: relative;
        z-index: 2;
    }

    .hero-search-card .input-group {
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .hero-search-card .input-group-text {
        background: #fff !important;
        border: none !important;
        padding: 0 1rem !important;
    }

    .hero-search-card .input-group-text i {
        color: var(--primary);
        font-size: 1.1rem;
    }

    .hero-search-card input {
        border: none !important;
        padding: .85rem 1rem !important;
        font-size: .95rem;
        color: var(--text-main);
    }

    .hero-search-card input::placeholder {
        color: #cbd5e1;
    }

    .hero-search-card button {
        background: var(--primary) !important;
        color: #fff !important;
        font-weight: 700;
        border: none !important;
        padding: .85rem 1.5rem !important;
        transition: all .3s;
    }

    .hero-search-card button:hover {
        background: var(--primary-dark) !important;
        transform: translateX(2px);
    }

    .hero-search-card .security-text {
        font-size: .75rem;
        color: rgba(255,255,255,.7);
        margin-top: 1rem;
        position: relative;
        z-index: 2;
    }

    /* ─── Alur ─────────────────────────────────────────────── */
    .section-label {
        font-size: .75rem;
        font-weight: 700;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: .5rem;
        font-family: 'Poppins', sans-serif;
    }

    .section-title {
        font-size: clamp(1.8rem, 3vw, 2.4rem);
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: .75rem;
        font-family: 'Poppins', sans-serif;
    }

    .section-subtitle {
        color: var(--text-muted);
        font-size: 1rem;
        line-height: 1.7;
    }

    .alur-section {
        padding: 6rem 0;
        background: #ffffff;
        border: none;
        position: relative;
        overflow: hidden;
    }

    .alur-section::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: 
            linear-gradient(rgba(226, 232, 240, 0.4) 1px, transparent 1px),
            linear-gradient(90deg, rgba(226, 232, 240, 0.4) 1px, transparent 1px);
        background-size: 40px 40px;
        z-index: 0;
        pointer-events: none;
    }

    .alur-section::after {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(8, 145, 178, 0.04), transparent 70%);
        top: 50%;
        left: -200px;
        border-radius: 50%;
        transform: translateY(-50%);
        z-index: 0;
    }

    .alur-container {
        position: relative;
        z-index: 1;
    }

    .alur-step {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        perspective: 900px;
    }

    .alur-icon {
        width: 100px;
        height: 100px;
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 2;
        transition: transform .35s ease, box-shadow .35s ease, border-color .35s ease;
        background: linear-gradient(145deg, #ffffff, #f8fafc);
        border: 2px solid var(--border);
        box-shadow: 0 14px 34px rgba(15, 23, 42, 0.09), inset 0 1px 0 rgba(255,255,255,0.9);
        transform: rotateX(8deg) rotateY(-10deg);
        transform-style: preserve-3d;
    }

    .alur-icon::before {
        content: '';
        position: absolute;
        inset: 10px -12px -14px 14px;
        background: rgba(15, 23, 42, 0.08);
        border-radius: 24px;
        filter: blur(10px);
        opacity: .75;
        transition: opacity .3s, transform .3s;
        z-index: -1;
    }

    .alur-icon::after {
        content: '';
        position: absolute;
        inset: 1px;
        border-radius: 22px;
        background: linear-gradient(135deg, rgba(255,255,255,0.7), transparent 48%, rgba(255,255,255,0.25));
        pointer-events: none;
    }

    .alur-icon:hover {
        transform: translateY(-12px) rotateX(3deg) rotateY(-4deg) scale(1.04);
        box-shadow: 0 24px 52px rgba(15, 23, 42, 0.14), inset 0 1px 0 rgba(255,255,255,0.9);
    }

    .alur-icon.step-1 { color: var(--primary); background: rgba(30, 64, 175, 0.08); border-color: rgba(30, 64, 175, 0.3); }
    .alur-icon.step-2 { color: var(--accent); background: rgba(8, 145, 178, 0.08); border-color: rgba(8, 145, 178, 0.3); }
    .alur-icon.step-3 { color: var(--warning); background: rgba(217, 119, 6, 0.08); border-color: rgba(217, 119, 6, 0.3); }
    .alur-icon.step-4 { color: var(--success); background: rgba(5, 150, 105, 0.08); border-color: rgba(5, 150, 105, 0.3); }
    .alur-icon.step-5 { color: var(--primary); background: rgba(30, 64, 175, 0.08); border-color: rgba(30, 64, 175, 0.3); }

    .alur-icon:hover.step-1 { background: rgba(30, 64, 175, 0.12); border-color: var(--primary); }
    .alur-icon:hover.step-2 { background: rgba(8, 145, 178, 0.12); border-color: var(--accent); }
    .alur-icon:hover.step-3 { background: rgba(217, 119, 6, 0.12); border-color: var(--warning); }
    .alur-icon:hover.step-4 { background: rgba(5, 150, 105, 0.12); border-color: var(--success); }
    .alur-icon:hover.step-5 { background: rgba(30, 64, 175, 0.12); border-color: var(--primary); }

    .alur-number {
        position: absolute;
        top: -12px;
        right: -12px;
        width: 28px;
        height: 28px;
        background: var(--accent);
        color: #fff;
        border-radius: 50%;
        font-size: .7rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(8, 145, 178, 0.2);
        z-index: 3;
    }

    .alur-icon i {
        position: relative;
        z-index: 2;
        filter: drop-shadow(0 8px 12px rgba(15, 23, 42, 0.12));
    }

    .alur-step-title {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: .3rem;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
    }

    .alur-step-desc {
        font-size: .85rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* Connector lines - 3D effect */
    .alur-connector {
        position: absolute;
        top: 40px;
        left: calc(50% + 40px);
        right: calc(-50% + 40px);
        height: 2px;
        background: var(--border);
        z-index: 0;
    }

    .alur-step:last-child .alur-connector { display: none; }

    /* ─── Jenis Bantuan ─────────────────────────────────────── */
    .bantuan-section {
        padding: 6rem 0;
    }

    .bantuan-card {
        border: 2px solid var(--border);
        border-radius: 20px;
        padding: 2rem 1.75rem;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        background: #fff;
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
        transform: perspective(900px) rotateX(0deg) rotateY(0deg);
    }

    .bantuan-card::before {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(8, 145, 178, 0.1), transparent 70%);
        top: -40px;
        right: -40px;
        border-radius: 50%;
        z-index: 0;
    }

    .bantuan-card:hover {
        border-color: var(--accent);
        box-shadow: 0 18px 42px rgba(8, 145, 178, 0.14);
        transform: perspective(900px) translateY(-10px) rotateX(3deg) rotateY(-3deg);
    }

    .bantuan-icon {
        width: 60px;
        height: 60px;
        background: var(--accent-light);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--accent);
        margin-bottom: 1rem;
        border: 2px solid var(--accent);
        position: relative;
        z-index: 1;
    }

    .bantuan-kode {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--accent);
        background: var(--accent-light);
        padding: .3rem .8rem;
        border-radius: 8px;
        display: inline-block;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .bantuan-card-title {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: .5rem;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
        position: relative;
        z-index: 1;
    }

    .bantuan-card-desc {
        font-size: .875rem;
        color: var(--text-muted);
        line-height: 1.6;
        position: relative;
        z-index: 1;
    }

    /* ─── CTA Banner ─────────────────────────────────────────── */
    .cta-section {
        background: var(--primary);
        border-radius: 28px;
        padding: 4rem 2rem;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(30, 64, 175, 0.2);
    }

    .cta-section::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.08), transparent 70%);
        top: -100px;
        right: -80px;
        border-radius: 50%;
        z-index: 0;
    }

    .cta-section::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.06), transparent 70%);
        bottom: -100px;
        left: -50px;
        border-radius: 50%;
        z-index: 0;
    }

    .cta-content {
        position: relative;
        z-index: 1;
    }

    .cta-section h2 {
        font-weight: 800;
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        color: #fff;
        margin-bottom: .75rem;
        font-family: 'Poppins', sans-serif;
    }

    .cta-section p {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        font-size: 1rem;
        line-height: 1.7;
    }

    .cta-btn {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: #fff !important;
        padding: .85rem 2rem !important;
        border-radius: 14px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        transition: all .3s;
    }

    .cta-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.7);
        transform: translateY(-2px);
    }

    /* ─── Statistik Section ─────────────────────────────────────── */
    .stats-section {
        padding: 6rem 0;
        background: #ffffff;
    }

    .stat-item {
        text-align: center;
        padding: 2rem 1.5rem;
        background: #ffffff;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease, background .3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
        transform: perspective(900px) rotateX(0deg) rotateY(0deg);
    }

    .stat-item::before {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(8, 145, 178, 0.08), transparent 70%);
        top: -50px;
        right: -50px;
        border-radius: 50%;
        z-index: 0;
    }

    .stat-item:hover {
        border-color: var(--accent);
        box-shadow: 0 18px 42px rgba(8, 145, 178, 0.13);
        transform: perspective(900px) translateY(-10px) rotateX(3deg) rotateY(-3deg);
        background: rgba(8, 145, 178, 0.02);
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        background: var(--accent-light);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--accent);
        margin: 0 auto 1rem;
        border: 2px solid var(--accent);
        position: relative;
        z-index: 1;
    }

    .stat-value {
        font-size: clamp(1.8rem, 4vw, 2.5rem);
        font-weight: 800;
        color: var(--accent);
        margin-bottom: .5rem;
        font-family: 'Poppins', sans-serif;
    }

    .stat-label {
        font-size: .95rem;
        color: var(--text-main);
        font-weight: 600;
        margin-bottom: .3rem;
    }

    .stat-desc {
        font-size: .85rem;
        color: var(--text-muted);
        line-height: 1.5;
    }

    /* ─── Eligibility Section ─────────────────────────────────────── */
    .eligibility-section {
        padding: 6rem 0;
        background: var(--bg-subtle);
        border-top: 1px solid var(--border);
        border-bottom: 1px solid var(--border);
    }

    .eligibility-section::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(30, 64, 175, 0.03), transparent 70%);
        top: 50%;
        right: -200px;
        border-radius: 50%;
        transform: translateY(-50%);
        z-index: 0;
        pointer-events: none;
    }

    .eligibility-container {
        position: relative;
        z-index: 1;
    }

    .eligibility-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 2rem;
        margin-top: 3rem;
    }

    .eligibility-card {
        background: #ffffff;
        border: 1.5px solid var(--border);
        border-radius: 18px;
        padding: 2rem 1.75rem;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
        transform: perspective(900px) rotateX(0deg) rotateY(0deg);
    }

    .eligibility-card::before {
        content: '';
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(30, 64, 175, 0.1), transparent 70%);
        top: -40px;
        left: -40px;
        border-radius: 50%;
        z-index: 0;
    }

    .eligibility-card:hover {
        border-color: var(--primary);
        box-shadow: 0 18px 42px rgba(30, 64, 175, 0.13);
        transform: perspective(900px) translateY(-10px) rotateX(3deg) rotateY(-3deg);
    }

    .eligibility-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .eligibility-icon {
        width: 50px;
        height: 50px;
        background: rgba(30, 64, 175, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        color: var(--primary);
        flex-shrink: 0;
    }

    .eligibility-title {
        font-weight: 700;
        font-size: 1.1rem;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
    }

    .eligibility-list {
        list-style: none;
        padding: 0;
        margin: 0;
        position: relative;
        z-index: 1;
    }

    .eligibility-list li {
        padding: .5rem 0;
        padding-left: 1.5rem;
        color: var(--text-muted);
        font-size: .9rem;
        line-height: 1.5;
        position: relative;
    }

    .eligibility-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: var(--success);
        font-weight: 800;
    }

    /* ─── FAQ Section ─────────────────────────────────────── */
    .faq-section {
        padding: 6rem 0;
        background: #ffffff;
    }

    .faq-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .faq-item {
        background: #ffffff;
        border: 1.5px solid var(--border);
        border-radius: 16px;
        padding: 1.75rem;
        margin-bottom: 1rem;
        transition: all .3s;
        overflow: hidden;
        position: relative;
    }

    .faq-item::before {
        content: '';
        position: absolute;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(8, 145, 178, 0.06), transparent 70%);
        top: -40px;
        right: -40px;
        border-radius: 50%;
        z-index: 0;
    }

    .faq-item.active {
        border-color: var(--accent);
        background: rgba(8, 145, 178, 0.02);
        box-shadow: 0 8px 20px rgba(8, 145, 178, 0.08);
    }

    .faq-question {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
        cursor: pointer;
        position: relative;
        z-index: 1;
        user-select: none;
        transition: all .3s;
    }

    .faq-question:hover {
        color: var(--accent);
    }

    .faq-question-text {
        font-weight: 700;
        font-size: 1rem;
        color: var(--text-main);
        font-family: 'Poppins', sans-serif;
        flex: 1;
    }

    .faq-toggle {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--accent-light);
        border: 1.5px solid var(--accent);
        border-radius: 8px;
        color: var(--accent);
        font-weight: 800;
        flex-shrink: 0;
        transition: all .3s;
    }

    .faq-item.active .faq-toggle {
        transform: rotate(180deg);
    }

    .faq-answer {
        color: var(--text-muted);
        font-size: .95rem;
        line-height: 1.8;
        margin-top: 1rem;
        max-height: 0;
        overflow: hidden;
        transition: all .3s;
        position: relative;
        z-index: 1;
    }

    .faq-item.active .faq-answer {
        max-height: 500px;
        opacity: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-stat-group {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .alur-connector {
            display: none !important;
        }

        .hero-search-card,
        .alur-icon {
            transform: none;
        }

        .hero-right-card::before,
        .hero-right-card::after {
            display: none;
        }

        .bantuan-card {
            padding: 1.5rem 1.25rem;
        }

        .eligibility-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .stat-item {
            padding: 1.5rem 1.25rem;
        }
    }

    @media (max-width: 576px) {
        .hero-buttons {
            flex-direction: column;
        }

        .btn-primary-lp, .btn-secondary-lp {
            width: 100%;
            justify-content: center;
        }

        .faq-question {
            flex-direction: column;
            gap: .5rem;
        }

        .faq-toggle {
            margin-left: auto;
        }
    }
</style>
@endpush

@section('content')

{{-- ─── Hero ─── --}}
<section id="home" class="hero-section">
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="hero-badge">
                    <i class="bi bi-shield-check-fill"></i>
                    Layanan Resmi Dinas Sosial
                </div>
                <h1 class="hero-title">
                    Sistem Informasi
                    <span class="accent-text">Pengajuan Bantuan Sosial</span>
                </h1>
                <p class="hero-subtitle">
                    Platform digital terintegrasi untuk transparansi penyaluran bantuan sosial kepada masyarakat. Mudah diakses, cepat, dan dapat dipantau secara real-time.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('cek-status') }}" class="btn-primary-lp">
                        <i class="bi bi-search"></i>
                        Cek Status Bantuan
                    </a>
                    <a href="#alur" class="btn-secondary-lp">
                        <i class="bi bi-arrow-down-circle"></i>
                        Lihat Alur Proses
                    </a>
                </div>
                <div class="hero-stat-group">
                    <div class="hero-stat-card">
                        <div class="stat-number"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-label">Penerima Bantuan</div>
                    </div>
                    <div class="hero-stat-card">
                        <div class="stat-number"><i class="bi bi-file-earmark-check-fill"></i></div>
                        <div class="stat-label">Pengajuan Aktif</div>
                    </div>
                    <div class="hero-stat-card">
                        <div class="stat-number"><i class="bi bi-truck"></i></div>
                        <div class="stat-label">Sudah Disalurkan</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 hero-right-card">
                <div class="hero-search-card">
                    <h5><i class="bi bi-search me-2"></i>Cek Status Bantuan</h5>
                    <p>Masukkan NIK Anda untuk melihat status pengajuan bantuan sosial.</p>
                    <form action="{{ route('status') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-credit-card-2-front"></i>
                            </span>
                            <input
                                type="text"
                                name="nik"
                                id="nik-hero"
                                class="form-control"
                                placeholder="Masukkan NIK 16 digit..."
                                maxlength="16"
                                inputmode="numeric"
                                value="{{ request('nik') }}"
                            >
                            <button type="submit" class="btn">
                                <i class="bi bi-search me-1"></i>
                                <span class="d-none d-sm-inline">Cari</span>
                            </button>
                        </div>
                        <p class="security-text">
                            <i class="bi bi-lock-fill me-1"></i>Data Anda aman. NIK hanya digunakan untuk pencarian status.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Alur Proses ─── --}}
<section id="alur" class="alur-section">
    <div class="container alur-container">
        <div class="text-center mb-5">
            <p class="section-label">Alur Proses</p>
            <h2 class="section-title">Bagaimana Bantuan Disalurkan?</h2>
            <p class="section-subtitle mx-auto" style="max-width:540px;">Setiap pengajuan melewati tahapan yang ketat untuk memastikan penyaluran bantuan tepat sasaran dan akuntabel.</p>
        </div>
        <div class="row g-4 row-cols-2 row-cols-md-5 justify-content-center">
            @php
            $steps = [
                ['icon'=>'bi-file-earmark-person-fill','class'=>'step-1','title'=>'Pengajuan','desc'=>'Petugas mengajukan calon penerima'],
                ['icon'=>'bi-clipboard2-check-fill','class'=>'step-2','title'=>'Survei','desc'=>'Verifikasi kondisi di lapangan'],
                ['icon'=>'bi-patch-check-fill','class'=>'step-3','title'=>'Verifikasi','desc'=>'Admin meninjau hasil survei'],
                ['icon'=>'bi-person-check-fill','class'=>'step-4','title'=>'Persetujuan','desc'=>'Pimpinan memberi keputusan'],
                ['icon'=>'bi-truck','class'=>'step-5','title'=>'Penyaluran','desc'=>'Bantuan diterima penerima'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="col">
                <div class="alur-step">
                    @if($i < count($steps) - 1)
                    <div class="alur-connector"></div>
                    @endif
                    <div class="alur-icon {{ $step['class'] }}">
                        <i class="bi {{ $step['icon'] }}"></i>
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
<section id="bantuan" class="bantuan-section">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-label">Program Bantuan</p>
            <h2 class="section-title">Jenis Bantuan Tersedia</h2>
            <p class="section-subtitle mx-auto" style="max-width:520px;">Program bantuan sosial yang aktif dan dapat diajukan oleh masyarakat yang memenuhi syarat dan ketentuan berlaku.</p>
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
                    <p class="bantuan-card-desc mb-0">Program bantuan sosial resmi dari Dinas Sosial yang dirancang untuk membantu masyarakat.</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─── Statistik ─── --}}
<section id="statistics" class="stats-section">
    <div class="container">
        <div class="text-center mb-5">
            <p class="section-label">Statistik Sistem</p>
            <h2 class="section-title">Pencapaian Kami</h2>
            <p class="section-subtitle mx-auto" style="max-width:540px;">Transparansi penuh dalam penyaluran bantuan sosial kepada seluruh masyarakat yang berhak.</p>
        </div>
        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-4">
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="stat-value">1,250</div>
                    <div class="stat-label">Penerima Bantuan</div>
                    <p class="stat-desc">Telah menerima bantuan sosial</p>
                </div>
            </div>
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                    <div class="stat-value">456</div>
                    <div class="stat-label">Pengajuan Aktif</div>
                    <p class="stat-desc">Sedang dalam proses verifikasi</p>
                </div>
            </div>
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="stat-value">89%</div>
                    <div class="stat-label">Akurasi Verifikasi</div>
                    <p class="stat-desc">Data terverifikasi dengan akurat</p>
                </div>
            </div>
            <div class="col">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div class="stat-value">30 hari</div>
                    <div class="stat-label">Waktu Proses</div>
                    <p class="stat-desc">Rata-rata pengajuan diproses</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ─── Siapa yang Berhak? ─── --}}
<section id="eligibility" class="eligibility-section">
    <div class="container eligibility-container">
        <div class="text-center mb-5">
            <p class="section-label">Persyaratan</p>
            <h2 class="section-title">Siapa yang Berhak?</h2>
            <p class="section-subtitle mx-auto" style="max-width:540px;">Program bantuan sosial kami dirancang untuk membantu masyarakat yang memenuhi kriteria tertentu. Periksa kelayakan Anda.</p>
        </div>
        <div class="eligibility-grid">
            <div class="eligibility-card">
                <div class="eligibility-header">
                    <div class="eligibility-icon">
                        <i class="bi bi-person-check"></i>
                    </div>
                    <h3 class="eligibility-title">Identitas Diri</h3>
                </div>
                <ul class="eligibility-list">
                    <li>Warga negara Indonesia (WNI)</li>
                    <li>Memiliki KTP/Kartu Identitas yang valid</li>
                    <li>Domisili sesuai dengan wilayah layanan</li>
                    <li>Terdaftar dalam sistem data nasional</li>
                </ul>
            </div>
            <div class="eligibility-card">
                <div class="eligibility-header">
                    <div class="eligibility-icon">
                        <i class="bi bi-wallet-fill"></i>
                    </div>
                    <h3 class="eligibility-title">Kondisi Ekonomi</h3>
                </div>
                <ul class="eligibility-list">
                    <li>Pendapatan di bawah rata-rata</li>
                    <li>Tidak memiliki aset properti bernilai tinggi</li>
                    <li>Tidak menerima bantuan pemerintah lain</li>
                    <li>Memiliki tanggungan keluarga</li>
                </ul>
            </div>
            <div class="eligibility-card">
                <div class="eligibility-header">
                    <div class="eligibility-icon">
                        <i class="bi bi-hospital"></i>
                    </div>
                    <h3 class="eligibility-title">Kondisi Kesehatan</h3>
                </div>
                <ul class="eligibility-list">
                    <li>Mengalami kondisi kesehatan tertentu</li>
                    <li>Membutuhkan bantuan kesehatan mendesak</li>
                    <li>Memiliki surat keterangan dari medis</li>
                    <li>Tidak mampu biaya pengobatan</li>
                </ul>
            </div>
            <div class="eligibility-card">
                <div class="eligibility-header">
                    <div class="eligibility-icon">
                        <i class="bi bi-house-fill"></i>
                    </div>
                    <h3 class="eligibility-title">Kondisi Tempat Tinggal</h3>
                </div>
                <ul class="eligibility-list">
                    <li>Rumah dalam kondisi tidak layak huni</li>
                    <li>Tidak memiliki akses air bersih</li>
                    <li>Tidak memiliki sistem sanitasi yang baik</li>
                    <li>Membutuhkan perbaikan infrastruktur</li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ─── FAQ ─── --}}
<section id="faq" class="faq-section">
    <div class="container faq-container">
        <div class="text-center mb-5">
            <p class="section-label">Pertanyaan Umum</p>
            <h2 class="section-title">FAQ</h2>
            <p class="section-subtitle mx-auto" style="max-width:540px;">Jawaban atas pertanyaan yang sering diajukan tentang program bantuan sosial kami.</p>
        </div>
        <div class="faq-items">
            <div class="faq-item active">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span class="faq-question-text">Bagaimana cara mengajukan bantuan sosial?</span>
                    <div class="faq-toggle">▼</div>
                </div>
                <div class="faq-answer">
                    Untuk mengajukan bantuan sosial, Anda dapat menghubungi kantor Dinas Sosial setempat atau mengunjungi kantor pelayanan kami. Siapkan dokumen identitas (KTP/Kartu Identitas) dan surat keterangan yang diperlukan sesuai dengan jenis bantuan yang Anda ajukan. Petugas kami akan memandu Anda melalui proses pengajuan dengan lengkap.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span class="faq-question-text">Berapa lama waktu proses verifikasi?</span>
                    <div class="faq-toggle">▼</div>
                </div>
                <div class="faq-answer">
                    Waktu proses verifikasi bantuan sosial rata-rata berkisar 30 hari kerja. Proses ini meliputi tahap pengajuan, survei lapangan, verifikasi data, dan persetujuan oleh pimpinan. Status pengajuan Anda dapat dicek secara real-time melalui platform kami dengan memasukkan NIK Anda.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span class="faq-question-text">Apakah ada biaya untuk mengajukan bantuan?</span>
                    <div class="faq-toggle">▼</div>
                </div>
                <div class="faq-answer">
                    Tidak ada biaya sama sekali untuk mengajukan bantuan sosial. Program ini sepenuhnya gratis dan disponsori oleh pemerintah daerah. Jika ada seseorang yang meminta biaya untuk pengajuan, segera laporkan kepada kami karena hal tersebut merupakan indikasi penipuan.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span class="faq-question-text">Bagaimana cara cek status bantuan saya?</span>
                    <div class="faq-toggle">▼</div>
                </div>
                <div class="faq-answer">
                    Cek status bantuan Anda sangat mudah. Cukup klik tombol "Cek Status Bantuan" di halaman utama, masukkan NIK 16 digit Anda, dan sistem akan menampilkan status lengkap pengajuan Anda. Data diperbarui secara real-time sesuai dengan tahap proses yang sedang berjalan.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span class="faq-question-text">Bagaimana jika pengajuan saya ditolak?</span>
                    <div class="faq-toggle">▼</div>
                </div>
                <div class="faq-answer">
                    Jika pengajuan Anda ditolak, sistem akan menampilkan alasan penolakan dengan jelas. Anda dapat menghubungi kantor Dinas Sosial setempat untuk mendiskusikan alasan penolakan. Jika ada data yang kurang akurat, Anda dapat mengajukan keberatan dengan melengkapi dokumen pendukung yang diperlukan.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span class="faq-question-text">Apakah data saya aman?</span>
                    <div class="faq-toggle">▼</div>
                </div>
                <div class="faq-answer">
                    Keamanan data Anda adalah prioritas utama kami. Semua informasi pribadi dienkripsi dan hanya digunakan untuk keperluan verifikasi bantuan sosial. Data Anda tidak akan dibagikan kepada pihak ketiga tanpa persetujuan resmi. Sistem kami telah tersertifikasi dan memenuhi standar keamanan data internasional.
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function toggleFaq(element) {
    const faqItem = element.closest('.faq-item');
    const isActive = faqItem.classList.contains('active');
    
    // Close all other items
    document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.remove('active');
    });
    
    // Toggle current item
    if (!isActive) {
        faqItem.classList.add('active');
    }
}
</script>

{{-- ─── CTA ─── --}}
<section id="cta" style="padding: 5rem 0 6rem;">
    <div class="container">
        <div class="cta-section">
            <div class="cta-content">
                <p class="section-label" style="color: rgba(255, 255, 255, 0.8);">Mulai Sekarang</p>
                <h2>Ingin Tahu Status Bantuan Anda?</h2>
                <p>Cek status pengajuan bantuan sosial Anda hanya dengan memasukkan NIK. Gratis, aman, dan tanpa pendaftaran.</p>
                <a href="{{ route('cek-status') }}" class="cta-btn">
                    <i class="bi bi-search"></i>
                    Cek Status Bantuan Sekarang
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
