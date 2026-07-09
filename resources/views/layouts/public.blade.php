<!DOCTYPE html>
<html lang="id">
<head>
     <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}?v=2">


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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --accent: #0891b2;
            --accent-light: #cffafe;
            --success: #059669;
            --warning: #d97706;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg-subtle: #f8fafc;
        }

        * { box-sizing: border-box; }
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 96px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            background: #ffffff;
            margin: 0;
        }

        /* ─── Navbar ─────────────────────────────────── */
        .lp-navbar {
            background: transparent;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            border-bottom: 1px solid transparent;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: none;
            transition: background .28s ease, box-shadow .28s ease, border-color .28s ease, backdrop-filter .28s ease;
        }
        .lp-navbar.is-scrolled {
            background: rgba(255, 255, 255, 0.96);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom-color: rgba(226, 232, 240, 0.95);
            box-shadow: 0 16px 42px rgba(15, 23, 42, 0.09);
        }
        .lp-navbar .navbar-brand {
            font-weight: 800;
            font-size: 1.05rem;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .5rem;
            font-family: 'Poppins', sans-serif;
            transition: transform .25s ease;
        }
        .lp-navbar .navbar-brand:hover {
            transform: translateY(-1px);
        }
        .lp-navbar .navbar-shell {
            padding-top: 1rem;
            padding-bottom: 1rem;
            transition: padding .25s ease;
        }
        .lp-navbar.is-scrolled .navbar-shell {
            padding-top: .75rem;
            padding-bottom: .75rem;
        }
        .lp-navbar .navbar-brand .brand-logo {
            width: 42px;
            height: 42px;
            object-fit: contain;
            display: block;
        }

        .lp-navbar .nav-links {
            display: flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem;
            border: 1px solid transparent;
            background: transparent;
            border-radius: 999px;
            transition: background .28s ease, border-color .28s ease, box-shadow .28s ease;
        }
        .lp-navbar.is-scrolled .nav-links {
            border-color: rgba(226, 232, 240, 0.9);
            background: rgba(248, 250, 252, 0.78);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);
        }
        .lp-navbar .nav-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-weight: 700;
            font-size: .82rem;
            transition: all .3s;
            padding: .55rem .9rem;
            position: relative;
            border-radius: 999px;
        }
        .lp-navbar .nav-links a::after {
            display: none;
        }
        .lp-navbar .nav-links a:hover {
            color: var(--primary);
            background: #fff;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.06);
        }
        .lp-navbar .nav-links a.active {
            color: var(--primary);
            background: #fff;
            box-shadow: 0 8px 18px rgba(30, 64, 175, 0.1);
            outline: 2px solid rgba(30, 64, 175, 0.24);
        }
        .lp-navbar .nav-action {
            color: var(--text-muted);
            text-decoration: none;
            font-size: .875rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .55rem .75rem;
            border-radius: 999px;
            transition: all .25s ease;
        }
        .lp-navbar .nav-action:hover {
            color: var(--primary);
            background: #dbeafe;
        }
        .lp-navbar .btn-login {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: .75rem 1.4rem;
            border-radius: 12px;
            font-weight: 800;
            font-size: .875rem;
            text-decoration: none;
            transition: all .3s;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
        }
        .lp-navbar .btn-login:hover { 
            background: var(--primary-dark);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(30, 64, 175, 0.3);
        }

        @media (max-width: 767.98px) {
            html { scroll-padding-top: 74px; }

            .lp-navbar .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }

            .lp-navbar .navbar-shell,
            .lp-navbar.is-scrolled .navbar-shell {
                padding-top: .7rem;
                padding-bottom: .7rem;
            }

            .lp-navbar .navbar-brand {
                font-size: .95rem;
                gap: .45rem;
            }

            .lp-navbar .navbar-brand .brand-logo {
                width: 34px;
                height: 34px;
            }

            .lp-navbar .btn-login {
                padding: .62rem .85rem;
                font-size: .8rem;
                box-shadow: 0 8px 18px rgba(30, 64, 175, 0.18);
            }

            .lp-navbar .btn-login .login-label {
                display: none;
            }
        }

        /* ─── Footer ─────────────────────────────────── */
        .lp-footer {
            background: var(--text-main);
            color: #94a3b8;
            padding: 3.5rem 0 2rem;
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
            font-family: 'Poppins', sans-serif;
        }
        .lp-footer .footer-brand .brand-icon {
            width: 32px;
            height: 32px;
            object-fit: contain;
            display: block;
        }
        .lp-footer a { color: #94a3b8; text-decoration: none; transition: color .2s; }
        .lp-footer a:hover { color: #fff; }
        .lp-footer .footer-divider { border-color: #1e293b; margin: 2rem 0 1.5rem; }
        .lp-footer .footer-bottom { color: #475569; font-size: .8rem; }

        /* ─── Buttons ─────────────────────────────────── */
        .btn-primary-lp {
            background: var(--primary);
            color: #fff !important;
            border: none;
            padding: .75rem 1.8rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .3s;
            box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
        }
        .btn-primary-lp:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(30, 64, 175, 0.3);
            color: #fff !important;
            background: var(--primary-dark);
        }
        .btn-secondary-lp {
            background: transparent;
            color: #fff !important;
            border: 2px solid rgba(255,255,255,.4);
            padding: .7rem 1.7rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: .95rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            transition: all .3s;
        }
        .btn-secondary-lp:hover {
            background: rgba(255,255,255,.12);
            border-color: rgba(255,255,255,.7);
            transform: translateY(-2px);
        }

        @yield('page_styles')
    </style>

    @stack('styles')
</head>
<body>

    @unless(View::hasSection('hide_navbar'))
        {{-- ─── Navbar ─── --}}
        <nav class="lp-navbar">
            <div class="container">
                <div class="navbar-shell d-flex align-items-center justify-content-between">
                    <a href="{{ route('landing') }}" class="navbar-brand">
                        <img src="{{ asset('logo.png') }}" alt="SiBansos" class="brand-logo">
                        SiBansos
                    </a>
                    <div class="d-none d-lg-flex align-items-center gap-4" style="flex:1;margin-left:3rem;">
                        <div class="nav-links">
                            <a href="#home">Home</a>
                            <a href="#alur">Alur</a>
                            <a href="#statistics">Statistik</a>
                            <a href="#eligibility">Syarat</a>
                            <a href="#faq">FAQ</a>
                            <a href="#cta">Kontak</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('cek-status') }}" class="nav-action d-none d-sm-inline-flex">
                            <i class="bi bi-search me-1"></i>Cek Status
                        </a>
                        <a href="{{ route('login') }}" class="btn-login">
                            <i class="bi bi-box-arrow-in-right"></i><span class="login-label">Login</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>
    @endunless

    {{-- ─── Page Content ─── --}}
    @yield('content')

    @unless(View::hasSection('hide_footer'))
        {{-- ─── Footer ─── --}}
        <footer class="lp-footer">
            <div class="container">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="footer-brand mb-2">
                            <img src="{{ asset('logo.png') }}" alt="SiBansos" class="brand-icon">
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
    @endunless

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (() => {
            const navbar = document.querySelector('.lp-navbar');
            const links = document.querySelectorAll('.lp-navbar .nav-links a[href^="#"]');
            const sections = Array.from(links)
                .map(link => document.querySelector(link.getAttribute('href')))
                .filter(Boolean);

            const updateNavbar = () => {
                navbar?.classList.toggle('is-scrolled', window.scrollY > 16);

                let activeId = '';
                sections.forEach(section => {
                    const top = section.getBoundingClientRect().top;
                    if (top <= 130) activeId = section.id;
                });

                links.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('href') === `#${activeId}`);
                });
            };

            links.forEach(link => {
                link.addEventListener('click', event => {
                    const target = document.querySelector(link.getAttribute('href'));
                    if (!target) return;

                    event.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    history.replaceState(null, '', link.getAttribute('href'));
                });
            });

            updateNavbar();
            window.addEventListener('scroll', updateNavbar, { passive: true });
        })();
    </script>
    @stack('scripts')
</body>
</html>
