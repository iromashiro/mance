<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MANCE - Muara Enim Smart City</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-primary-50 via-white to-primary-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full px-6">
        <div class="text-center">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="bg-primary-600 rounded-full p-6">
                    <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Title -->
            <h1 class="text-4xl font-bold text-gray-900 mb-2">MANCE</h1>
            <p class="text-lg text-gray-600 mb-8">Muara Enim Smart City</p>

            <!-- Description -->
            <p class="text-sm text-gray-500 mb-8">
                Platform layanan publik dan pengaduan masyarakat Kabupaten Muara Enim
            </p>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @if(auth()->check())
                <a href="{{ route('dashboard') }}"
                    class="block w-full px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Dashboard
                </a>
                @else
                <a href="{{ route('login') }}"
                    class="block w-full px-4 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="block w-full px-4 py-3 bg-white border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition">
                    Daftar Akun Baru
                </a>
                @endif
            </div>

            <!-- Features -->
            <div class="mt-12 grid grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <svg class="h-8 w-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                            </path>
                        </svg>
                        <p class="text-xs text-gray-600">Pengaduan Online</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <svg class="h-8 w-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                            </path>
                        </svg>
                        <p class="text-xs text-gray-600">Layanan Publik</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <svg class="h-8 w-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        <p class="text-xs text-gray-600">Berita Terkini</p>
                    </div>
                </div>
                <div class="text-center">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <svg class="h-8 w-8 text-primary-600 mx-auto mb-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                        <p class="text-xs text-gray-600">Notifikasi</p>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-12 text-xs text-gray-500">
                © 2025 Pemerintah Kabupaten Muara Enim
            </div>
        </div>
    </div>
</body>

</html>