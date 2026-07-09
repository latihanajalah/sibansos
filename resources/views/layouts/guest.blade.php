<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
         <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}?v=2">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Semabako') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                background:
                    linear-gradient(115deg, rgba(255,255,255,0.96) 0%, rgba(255,255,255,0.9) 54%, rgba(236,253,245,0.62) 100%),
                    repeating-linear-gradient(135deg, rgba(8,145,178,0.05) 0 1px, transparent 1px 20px),
                    linear-gradient(135deg, #f8fafc 0%, #eef6ff 50%, #ecfeff 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Poppins', sans-serif;
                padding: 2rem 1rem;
            }

            .login-container {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0;
                background: white;
                border-radius: 1.75rem;
                overflow: hidden;
                box-shadow: 0 24px 70px rgba(30, 64, 175, 0.14), 0 0 1px rgba(15, 23, 42, 0.08);
                max-width: 1000px;
                width: 90%;
                border: 1px solid rgba(226, 232, 240, 0.9);
            }

            .login-left {
                background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
                padding: 3rem 2rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                position: relative;
                overflow: hidden;
            }

            .login-left::before {
                content: '';
                position: absolute;
                width: 300px;
                height: 300px;
                background: radial-gradient(circle, rgba(255,255,255,0.14), transparent 70%);
                border-radius: 50%;
                top: -100px;
                right: -100px;
            }

            .login-left::after {
                content: '';
                position: absolute;
                width: 200px;
                height: 200px;
                background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
                border-radius: 50%;
                bottom: -50px;
                left: -50px;
            }

            .login-left-content {
                position: relative;
                z-index: 1;
                text-align: center;
            }

            .login-left h2 {
                font-size: 2rem;
                font-weight: 800;
                margin-bottom: 1rem;
                color: white;
            }

            .login-left p {
                font-size: 1.1rem;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 2rem;
                line-height: 1.6;
            }

            .login-icon {
                width: 118px;
                height: 118px;
                background: rgba(255, 255, 255, 0.12);
                border-radius: 1.5rem;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 2rem;
                border: 1px solid rgba(255, 255, 255, 0.22);
                box-shadow: inset 0 1px 0 rgba(255,255,255,0.14), 0 18px 40px rgba(15,23,42,0.16);
            }

            .login-icon img {
                width: 82px;
                height: 82px;
                object-fit: contain;
            }

            .login-right {
                padding: 3rem;
                display: flex;
                flex-direction: column;
                justify-content: center;
                background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            }

            .login-right h1 {
                font-size: 1.75rem;
                font-weight: 800;
                margin-bottom: 0.5rem;
                color: #0f172a;
            }

            .login-right p {
                color: #6B7280;
                margin-bottom: 2rem;
                font-size: 1rem;
            }

            .form-group {
                margin-bottom: 1.5rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.5rem;
                font-weight: 700;
                color: #0f172a;
            }

            .form-group input {
                width: 100%;
                padding: 0.875rem;
                border: 1.5px solid #e2e8f0;
                border-radius: 0.85rem;
                font-family: 'Poppins', sans-serif;
                font-size: 1rem;
                transition: all 0.3s;
            }

            .form-group input:focus {
                outline: none;
                border-color: #1e40af;
                box-shadow: 0 0 0 4px rgba(30, 64, 175, 0.1);
            }

            .form-group input.is-invalid {
                border-color: #dc2626;
                box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.08);
            }

            .checkbox-group {
                display: flex;
                align-items: center;
                margin-bottom: 1.5rem;
                gap: 0.75rem;
            }

            .checkbox-group input[type="checkbox"] {
                width: 18px;
                height: 18px;
                cursor: pointer;
                accent-color: #1e40af;
            }

            .checkbox-group label {
                margin: 0;
                cursor: pointer;
                font-size: 0.95rem;
                color: #6B7280;
            }

            .btn-login {
                width: 100%;
                padding: 0.875rem;
                background: #1e40af;
                color: white;
                border: none;
                border-radius: 0.85rem;
                font-size: 1rem;
                font-weight: 800;
                cursor: pointer;
                transition: all 0.3s;
                margin-bottom: 1rem;
                box-shadow: 0 4px 12px rgba(30, 64, 175, 0.2);
            }

            .btn-login:hover {
                background: #1e3a8a;
                transform: translateY(-3px);
                box-shadow: 0 8px 24px rgba(30, 64, 175, 0.3);
            }

            .btn-login:active {
                transform: translateY(0);
            }

            .forgot-password {
                text-align: center;
                margin-bottom: 1.5rem;
            }

            .forgot-password a {
                color: #1e40af;
                text-decoration: none;
                font-size: 0.95rem;
                transition: all 0.3s;
            }

            .forgot-password a:hover {
                text-decoration: underline;
            }

            .register-link {
                text-align: center;
                padding-top: 1.5rem;
                border-top: 1px solid #E5E7EB;
            }

            .register-link p {
                margin: 0;
                color: #6B7280;
                font-size: 0.95rem;
            }

            .register-link a {
                color: #1e40af;
                font-weight: 600;
                text-decoration: none;
            }

            .register-link a:hover {
                text-decoration: underline;
            }

            .error-message {
                background: #FEE2E2;
                border-left: 4px solid #dc2626;
                color: #991B1B;
                padding: 1rem;
                border-radius: 0.5rem;
                margin-bottom: 1.5rem;
                display: none;
            }

            .error-message.show {
                display: block;
                animation: fadeInUp 0.3s ease-out;
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(10px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @media (max-width: 768px) {
                .login-container {
                    grid-template-columns: 1fr;
                    width: 95%;
                }

                .login-left {
                    padding: 2rem 1.5rem;
                    display: none;
                }

                .login-right {
                    padding: 2rem 1.5rem;
                }

                .login-left h2 {
                    font-size: 1.5rem;
                }

                .login-right h1 {
                    font-size: 1.5rem;
                }

                .login-icon {
                    width: 80px;
                    height: 80px;
                    font-size: 2rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <div class="login-left">
                <div class="login-left-content">
                    <div class="login-icon">
                        <img src="{{ asset('logo.png') }}" alt="SiBansos">
                    </div>
                    <h2>Selamat Datang</h2>
                    <p>Sistem Informasi Pengajuan Bantuan Sosial yang Aman dan Terpercaya</p>
                </div>
            </div>

            <div class="login-right">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
