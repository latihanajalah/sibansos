<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Sistem Informasi Pengajuan Bantuan Sosial – cek status bantuan sosial Anda secara online.')">
    <title>@yield('title', 'Sistem Bansos') – SiBansos</title>

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1d4ed8;
            --primary-light: #eff6ff;
            --accent: #10b981;
            --accent-dark: #059669;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg-subtle: #f8fafc;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background: #fff;
            margin: 0;
        }

        /* ─── Navbar ─────────────────────────────────── */
        .lp-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .lp-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .lp-navbar .navbar-brand span.brand-icon {
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: #fff;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
        }
        .lp-navbar .btn-login {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: .45rem 1.1rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: .875rem;
            text-decoration: none;
            transition: background .2s;
        }
        .lp-navbar .btn-login:hover { background: var(--primary-dark); color: #fff; }

        /* ─── Footer ─────────────────────────────────── */
        .lp-footer {
            background: var(--text-main);
            color: #94a3b8;
            padding: 3rem 0 2rem;
            font-size: .875rem;
        }
        .lp-footer .footer-brand {
            font-weight: 700;
            color: #fff;
            font-size: 1rem;
            margin-bottom: .25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .lp-footer .footer-brand .brand-icon {
            background: linear-gradient(135deg, var(--primary), #7c3aed);
            color: #fff;
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
        }
        .lp-footer a { color: #94a3b8; text-decoration: none; transition: color .2s; }
        .lp-footer a:hover { color: #fff; }
        .lp-footer .footer-divider { border-color: #1e293b; margin: 2rem 0 1.5rem; }
        .lp-footer .footer-bottom { color: #475569; font-size: .8rem; }

        /* ─── Buttons ─────────────────────────────────── */
        .btn-primary-lp {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            color: #fff !important;
            border: none;
            padding: .75rem 1.8rem;
            border-radius: 10px;
            font-weight: 700;
            font-size: .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: transform .2s, box-shadow .2s;
            box-shadow: 0 4px 15px rgba(37,99,235,.35);
        }
        .btn-primary-lp:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37,99,235,.45);
            color: #fff !important;
        }
        .btn-secondary-lp {
            background: transparent;
            color: #fff !important;
            border: 2px solid rgba(255,255,255,.4);
            padding: .7rem 1.7rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: background .2s, border-color .2s;
        }
        .btn-secondary-lp:hover {
            background: rgba(255,255,255,.12);
            border-color: rgba(255,255,255,.7);
        }

        @yield('page_styles')
    </style>

    @stack('styles')
</head>
<body>

    {{-- ─── Navbar ─── --}}
    <nav class="lp-navbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-3">
                <a href="{{ route('landing') }}" class="navbar-brand">
                    <span class="brand-icon"><i class="bi bi-shield-check"></i></span>
                    SiBansos
                </a>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('cek-status') }}" class="text-decoration-none fw-500 text-secondary d-none d-sm-inline" style="font-size:.875rem;font-weight:500;">
                        <i class="bi bi-search me-1"></i>Cek Status
                    </a>
                    <a href="{{ route('login') }}" class="btn-login">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Login
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ─── Page Content ─── --}}
    @yield('content')

    {{-- ─── Footer ─── --}}
    <footer class="lp-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="footer-brand mb-2">
                        <span class="brand-icon"><i class="bi bi-shield-check"></i></span>
                        SiBansos
                    </div>
                    <p class="mb-0" style="line-height:1.7;">Sistem informasi terintegrasi untuk pengelolaan dan pemantauan program bantuan sosial secara transparan dan akuntabel.</p>
                </div>
                <div class="col-md-4">
                    <p class="text-white fw-600 mb-3" style="font-weight:600;">Tautan Cepat</p>
                    <ul class="list-unstyled mb-0" style="display:flex;flex-direction:column;gap:.5rem;">
                        <li><a href="{{ route('landing') }}"><i class="bi bi-house me-2"></i>Beranda</a></li>
                        <li><a href="{{ route('cek-status') }}"><i class="bi bi-search me-2"></i>Cek Status Bantuan</a></li>
                        <li><a href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Portal Petugas</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <p class="text-white fw-600 mb-3" style="font-weight:600;">Kontak</p>
                    <ul class="list-unstyled mb-0" style="display:flex;flex-direction:column;gap:.5rem;">
                        <li><i class="bi bi-telephone me-2"></i>(021) 000-0000</li>
                        <li><i class="bi bi-envelope me-2"></i>info@sibansos.go.id</li>
                        <li><i class="bi bi-geo-alt me-2"></i>Jl. Pahlawan No. 1, Kantor Dinas Sosial</li>
                    </ul>
                </div>
            </div>

            <hr class="footer-divider">

            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2 footer-bottom">
                <span>&copy; {{ date('Y') }} SiBansos – Sistem Informasi Bantuan Sosial. Hak Cipta Dilindungi.</span>
                <span>Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk masyarakat</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
