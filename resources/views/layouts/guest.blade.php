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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        try {
            if (localStorage.getItem('mance_splash_v1_seen')) {
                document.documentElement.setAttribute('data-splash-seen', '1');
            }
        } catch (e) {}
    </script>

    <style>
        [x-cloak] {
            display: none !important
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        @keyframes blob {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            25% {
                transform: translate(20px, -20px) scale(1.1);
            }

            50% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            75% {
                transform: translate(20px, 20px) scale(1.05);
            }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-blob {
            animation: blob 12s ease-in-out infinite;
        }

        .animate-blob-slow {
            animation: blob 18s ease-in-out infinite;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full font-sans antialiased">
    @include('components.splash')
    @include('components.page-loader')

    <div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
        <!-- Animated Background Blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute top-0 -left-4 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
            </div>
            <div
                class="absolute top-0 -right-4 w-96 h-96 bg-yellow-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-96 h-96 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob-slow animation-delay-4000">
            </div>
        </div>

        <!-- Content -->
        <div class="relative min-h-screen flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8">
            <!-- Logo Section -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <div class="relative group">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-primary-500 to-accent-500 rounded-2xl blur-xl opacity-50 group-hover:opacity-75 transition-opacity">
                        </div>
                        <div
                            class="relative bg-white/80 backdrop-blur-sm rounded-2xl px-6 py-3 shadow-xl border border-white/50">
                            <h1
                                class="text-3xl font-black bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
                                MANCE
                            </h1>
                            <p class="text-xs text-gray-600 font-medium mt-1">Muara Enim Smart City</p>
                        </div>
                    </div>
                </a>
            </div>

            @yield('header')

            <!-- Main Card -->
            <div class="w-full max-w-md">
                <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/50 overflow-hidden">
                    <div class="p-6 sm:p-8 lg:p-10">
                        @yield('content')
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    © 2025 Pemerintah Kabupaten Muara Enim
                </p>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
