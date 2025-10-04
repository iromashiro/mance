<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MANCE') - {{ config('app.name', 'MANCE') }}</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Prevent splash FOUC if already seen -->
    <script>
        try {
    {{-- Splash Screen (sekali per device) --}}

            if (localStorage.getItem('mance_splash_v1_seen')) {
                document.documentElement.setAttribute('data-splash-seen', '1');
            }
        } catch (e) {}
    </script>
    <!-- Hide Alpine.js x-cloak -->
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
    @stack('styles')
</head>

<body class="h-full">
    {{-- Global Page Loader --}}
    @include('components.page-loader')
    @include('components.splash')
    <div class="min-h-screen flex flex-col justify-center bg-gradient-to-br from-primary-50 via-white to-primary-50">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-primary-800">MANCE</h1>
                </div>
            </div>
            <h2 class="mt-4 text-center text-sm text-gray-600">
                Muara Enim Smart City
            </h2>
            @yield('header')
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-lg sm:px-10">
                @yield('content')
            </div>
            @stack('scripts')
        </div>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-500">
                © 2025 Pemerintah Kabupaten Muara Enim. All rights reserved.
            </p>
        </div>
    </div>
</body>

</html>
