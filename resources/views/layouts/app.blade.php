<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bansos Dashboard') }}</title>

    <!-- Favicon -->
     <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}?v=2">

    <!-- Google Fonts (Outfit) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Dashboard CSS -->
    @vite(['resources/css/dashboard.css'])
    
    @stack('css')
</head>
<body>
    <div id="sb-wrapper">
        <!-- Sidebar Navigation -->
        @include('layouts.sidebar')

        <!-- Main Content Area -->
        <div id="sb-content">
            <!-- Navbar Header -->
            @include('layouts.navbar')

            <!-- Main Content Container -->
            <main class="sb-main-body container-fluid">
                <!-- Reusable Components (Flash Messages, Breadcrumbs) -->
                @include('components.flash')
                
                @yield('content')
                
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>

            <!-- Footer -->
            @include('layouts.footer')
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS (Popper included) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- Custom Dashboard JS -->
    @vite(['resources/js/dashboard.js'])

    @stack('js')
</body>
</html>
