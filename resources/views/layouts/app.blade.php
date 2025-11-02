<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MANCE') - {{ config('app.name', 'MANCE') }}</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#6366f1">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- Modern Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite('resources/js/app.js')

    <!-- Prevent splash FOUC -->
    <script>
        try {
            if (localStorage.getItem('mance_splash_v2_seen')) {
                document.documentElement.setAttribute('data-splash-seen', '1');
            }
        } catch (e) {}
    </script>

    <!-- Modern Clean Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f5f5f4;
        }

        ::-webkit-scrollbar-thumb {
            background: #d6d3d1;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a29e;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Modern button styles */
        .btn-primary {
            @apply bg-primary-600 text-white px-4 py-2 rounded-lg font-medium hover: bg-primary-700 active:bg-primary-800 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2;
        }

        .btn-secondary {
            @apply bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover: bg-gray-200 active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2;
        }

        /* Clean card style */
        .card {
            @apply bg-white rounded-xl shadow-sm border border-gray-200 hover: shadow-md transition-shadow;
        }

        /* Modern input style */
        .input {
            @apply border-gray-300 rounded-lg focus: ring-2 focus:ring-primary-500 focus:border-primary-500;
        }
    </style>

    @stack('styles')
</head>

<body class="h-full font-sans bg-gray-50 text-gray-900">
    {{-- Splash Screen --}}
    @include('components.splash')

    {{-- Walkthrough --}}
    @include('components.walkthrough')

    {{-- Page Loader --}}
    @include('components.page-loader')

    <div x-data="{
        sidebarOpen: false,
        showNotifications: false,
        showProfile: false,
        showArunaModal: false
    }" class="min-h-screen flex flex-col">

        <!-- Modern Minimal Header -->
        <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-gray-200">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left Section -->
                    <div class="flex items-center space-x-8">
                        <!-- Logo -->
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <div class="relative">
                                <div
                                    class="absolute -inset-1 bg-primary-500/20 rounded-xl blur-md opacity-0 group-hover:opacity-100 transition-opacity">
                                </div>
                                <img src="{{ asset('mance-logo.png') }}" alt="MANCE" class="relative h-10 w-auto"
                                    decoding="async" fetchpriority="high" />
                            </div>
                        </a>

                        <!-- Desktop Navigation -->
                        <nav class="hidden lg:flex items-center space-x-1">
                            <a href="{{ route('dashboard') }}"
                                class="@if(request()->routeIs('dashboard')) bg-gray-100 text-gray-900 @else text-gray-600 hover:text-gray-900 @endif px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Dashboard
                            </a>
                            <a href="{{ route('applications.index') }}"
                                class="@if(request()->routeIs('applications.*')) bg-gray-100 text-gray-900 @else text-gray-600 hover:text-gray-900 @endif px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Layanan
                            </a>
                            <a href="{{ route('complaints.index') }}"
                                class="@if(request()->routeIs('complaints.*')) bg-gray-100 text-gray-900 @else text-gray-600 hover:text-gray-900 @endif px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Pengaduan
                            </a>
                            <a href="{{ route('news.index') }}"
                                class="@if(request()->routeIs('news.*')) bg-gray-100 text-gray-900 @else text-gray-600 hover:text-gray-900 @endif px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                Berita
                            </a>
                        </nav>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center space-x-2">
                        <!-- Notifications Button -->
                        <button @click="showNotifications = !showNotifications"
                            class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="absolute top-1.5 right-1.5 h-2 w-2 bg-accent-500 rounded-full"></span>
                            @endif
                        </button>

                        <!-- User Profile -->
                        <div class="relative">
                            <button @click="showProfile = !showProfile"
                                class="flex items-center p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <img class="h-8 w-8 rounded-lg"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&bold=true"
                                    alt="{{ auth()->user()->name }}">
                                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            <!-- Profile Dropdown -->
                            <div x-show="showProfile" @click.away="showProfile = false" x-cloak
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">

                                <!-- Profile Header -->
                                <div class="p-4 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <img class="h-10 w-10 rounded-lg"
                                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366f1&color=fff&bold=true"
                                            alt="{{ auth()->user()->name }}">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="p-2">
                                    <a href="{{ route('profile.index') }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        Profil
                                    </a>

                                    <a href="{{ route('favorites.index') }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        Favorit
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg">
                                        <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        Admin Panel
                                    </a>
                                    @endif

                                    <div class="my-2 border-t border-gray-100"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg">
                                            <svg class="mr-3 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Notifications Panel -->
        <div x-show="showNotifications" x-cloak class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4">
            <!-- Backdrop -->
            <div x-show="showNotifications" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/20 backdrop-blur-sm"
                @click="showNotifications = false"></div>

            <!-- Panel -->
            <div x-show="showNotifications" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden">

                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Notifikasi</h3>
                        <button @click="showNotifications = false" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    @if(auth()->user()->unreadNotifications()->count() > 0)
                    <p class="text-sm text-gray-500 mt-1">
                        {{ auth()->user()->unreadNotifications()->count() }} belum dibaca
                    </p>
                    @endif
                </div>

                <!-- Content -->
                <div class="max-h-96 overflow-y-auto">
                    @forelse(auth()->user()->unreadNotifications()->limit(5)->get() as $notification)
                    <a href="{{ route('notifications.index') }}" @click="showNotifications = false"
                        class="block px-6 py-4 hover:bg-gray-50 border-b border-gray-100 last:border-0">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ data_get($notification->data, 'title', 'Notifikasi') }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <div class="px-6 py-12 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-900">Tidak ada notifikasi</p>
                        <p class="text-xs text-gray-500 mt-1">Semua sudah dibaca</p>
                    </div>
                    @endforelse
                </div>

                <!-- Footer -->
                @if(auth()->user()->unreadNotifications()->count() > 0)
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                    <a href="{{ route('notifications.index') }}" @click="showNotifications = false"
                        class="block text-center text-sm font-medium text-primary-600 hover:text-primary-700">
                        Lihat Semua
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 pb-20 lg:pb-0">
            @yield('content')
        </main>

        <!-- Modern Bottom Navigation (Mobile) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40">
            <div class="grid grid-cols-5 h-16">
                <a href="{{ route('dashboard') }}"
                    class="flex flex-col items-center justify-center relative @if(request()->routeIs('dashboard')) text-primary-600 @else text-gray-400 @endif">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-xs mt-1">Home</span>
                    @if(request()->routeIs('dashboard'))
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-primary-600"></div>
                    @endif
                </a>

                <a href="{{ route('applications.index') }}"
                    class="flex flex-col items-center justify-center relative @if(request()->routeIs('applications.*')) text-primary-600 @else text-gray-400 @endif">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="text-xs mt-1">Layanan</span>
                    @if(request()->routeIs('applications.*'))
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-primary-600"></div>
                    @endif
                </a>

                <!-- Center Button: Aruna AI -->
                <button type="button" @click="showArunaModal = true"
                    class="flex flex-col items-center justify-center relative">
                    <div class="absolute -top-6 bg-white rounded-full p-1">
                        <div class="bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full p-3 shadow-lg">
                            <img src="{{ asset('aruna-ai.png') }}" alt="Aruna AI" class="h-8 w-8 rounded-full" />
                        </div>
                    </div>
                    <span class="text-xs mt-7 font-medium text-gray-900">Aruna</span>
                </button>

                <a href="{{ route('news.index') }}"
                    class="flex flex-col items-center justify-center relative @if(request()->routeIs('news.*')) text-primary-600 @else text-gray-400 @endif">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15" />
                    </svg>
                    <span class="text-xs mt-1">Berita</span>
                    @if(request()->routeIs('news.*'))
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-primary-600"></div>
                    @endif
                </a>

                <a href="{{ route('profile.index') }}"
                    class="flex flex-col items-center justify-center relative @if(request()->routeIs('profile.*')) text-primary-600 @else text-gray-400 @endif">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="text-xs mt-1">Profil</span>
                    @if(request()->routeIs('profile.*'))
                    <div class="absolute top-0 left-0 right-0 h-0.5 bg-primary-600"></div>
                    @endif
                </a>
            </div>
        </nav>

        <!-- Aruna AI Modal -->
        <div x-show="showArunaModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Backdrop -->
            <div x-show="showArunaModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"
                @click="showArunaModal = false"></div>

            <!-- Modal -->
            <div x-show="showArunaModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-sm bg-white rounded-2xl shadow-xl overflow-hidden">

                <!-- Header -->
                <div class="px-6 py-6 text-center border-b border-gray-100">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full mb-3">
                        <img src="{{ asset('aruna-ai.png') }}" alt="Aruna AI" class="h-12 w-12 rounded-full" />
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Aruna AI Assistant</h3>
                    <p class="text-sm text-gray-500 mt-1">Pilih cara berkomunikasi</p>
                </div>

                <!-- Options -->
                <div class="p-6 space-y-3">
                    <!-- Voice Call -->
                    <a href="{{ route('telpon') }}"
                        class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900">Panggilan Suara</h4>
                                <p class="text-xs text-gray-500">Bicara langsung dengan Aruna</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>

                    <!-- Chat -->
                    <a href="{{ route('aruna.ai') }}"
                        class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-secondary-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-secondary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-900">Chat Percakapan</h4>
                                <p class="text-xs text-gray-500">Kirim pesan teks ke Aruna</p>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                </div>

                <!-- Footer -->
                <div class="px-6 pb-6">
                    <button type="button" @click="showArunaModal = false"
                        class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notifications -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed bottom-24 lg:bottom-8 right-4 z-50 max-w-sm">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-success-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Berhasil</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed bottom-24 lg:bottom-8 right-4 z-50 max-w-sm">
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4 flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-error-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-error-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Error</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
    @endif

    @stack('scripts')
</body>

</html>