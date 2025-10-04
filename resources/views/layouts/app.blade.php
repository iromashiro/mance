<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MANCE') - {{ config('app.name', 'MANCE') }}</title>

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#7950f2">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite('resources/js/app.js')
    <!-- Prevent splash FOUC if already seen -->
    <script>
        try {
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
    {{-- Splash Screen (sekali per device) --}}


    <!-- Additional Styles -->
    @stack('styles')
</head>

<body class="h-full font-sans bg-gradient-to-br from-gray-50 via-white to-primary-50/20">
    {{-- Global Page Loader --}}
    {{-- Splash Screen (sekali per device) --}}
    @include('components.splash')

    {{-- Walkthrough Overlay (sekali per device setelah login) --}}
    @include('components.walkthrough')
    @include('components.page-loader')
    <div x-data="{ sidebarOpen: false, showNotifications: false, showProfile: false }" class="min-h-screen">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden">
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"
                @click="sidebarOpen = false">
            </div>
        </div>

        <!-- Modern Header with Glassmorphism -->
        <header class="sticky top-0 z-40 bg-white/70 backdrop-blur-xl border-b border-white/50 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left Section -->
                    <div class="flex items-center">
                        <!-- Mobile Menu Button -->
                        <button @click="sidebarOpen = true"
                            class="lg:hidden p-2 rounded-xl text-gray-500 hover:text-primary-600 hover:bg-primary-50 transition-all">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <div class="relative">
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-primary-600 to-accent-600 rounded-xl blur-lg opacity-70 animate-pulse-slow">
                                </div>
                                <h1
                                    class="relative text-2xl font-heading font-bold bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent px-3 py-1">
                                    MANCE
                                </h1>
                            </div>
                        </div>

                        <!-- Desktop Navigation -->
                        <nav class="hidden lg:flex lg:ml-10 space-x-1">
                            <a href="{{ route('dashboard') }}"
                                class="@if(request()->routeIs('dashboard')) nav-item-active @else nav-item @endif">
                                Dashboard
                            </a>
                            <a href="{{ route('applications.index') }}"
                                class="@if(request()->routeIs('applications.*')) nav-item-active @else nav-item @endif">
                                Layanan
                            </a>
                            <a href="{{ route('complaints.index') }}"
                                class="@if(request()->routeIs('complaints.*')) nav-item-active @else nav-item @endif">
                                Pengaduan
                            </a>
                            <a href="{{ route('news.index') }}"
                                class="@if(request()->routeIs('news.*')) nav-item-active @else nav-item @endif">
                                Berita
                            </a>
                        </nav>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center space-x-3">
                        <!-- Notifications -->
                        <div>
                            <button @click="showNotifications = !showNotifications"
                                class="relative p-2 rounded-xl text-gray-500 hover:text-primary-600 hover:bg-primary-50 transition-all group">
                                <svg class="h-6 w-6 group-hover:animate-wiggle" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                <span
                                    class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-gradient-to-r from-accent-500 to-pink-500 ring-2 ring-white animate-pulse"></span>
                                @endif
                            </button>
                        </div>

                        <!-- Notifications Modal -->
                        <div x-show="showNotifications" x-cloak
                            class="fixed inset-0 z-[60] flex items-start justify-center p-4 sm:p-6 pt-24 sm:pt-32"
                            @click.self="showNotifications = false" style="pointer-events: auto;">
                            <!-- Backdrop -->
                            <div x-show="showNotifications" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"
                                @click="showNotifications = false"></div>

                            <!-- Modal Content -->
                            <div x-show="showNotifications" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
                                <!-- Header -->
                                <div class="bg-gradient-to-r from-primary-600 to-accent-600 p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-white">Notifikasi</h3>
                                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                                <p class="text-sm text-white/80">
                                                    {{ auth()->user()->unreadNotifications()->count() }} belum dibaca
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                        <button @click="showNotifications = false"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/20 transition-colors">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="max-h-96 overflow-y-auto">
                                    @forelse(auth()->user()->unreadNotifications()->limit(5)->get() as $notification)
                                    <a href="{{ route('notifications.index') }}" @click="showNotifications = false"
                                        class="block px-4 py-3 hover:bg-gradient-to-r hover:from-primary-50 hover:to-accent-50 transition-all border-b border-gray-100 last:border-0">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center shadow-lg">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 line-clamp-2">
                                                    {{ data_get($notification->data, 'title', 'Notifikasi') }}
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                    @empty
                                    <div class="px-4 py-12 text-center">
                                        <div
                                            class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gray-100 mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">Tidak ada notifikasi</p>
                                        <p class="text-xs text-gray-500 mt-1">Semua notifikasi sudah dibaca</p>
                                    </div>
                                    @endforelse
                                </div>

                                <!-- Footer -->
                                @if(auth()->user()->unreadNotifications()->count() > 0)
                                <div class="p-3 bg-gray-50 border-t border-gray-100">
                                    <a href="{{ route('notifications.index') }}" @click="showNotifications = false"
                                        class="block w-full py-2.5 px-4 text-center text-sm font-semibold text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-xl transition-colors">
                                        Lihat Semua Notifikasi
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- User Profile -->
                        <div class="relative">
                            <button @click="showProfile = !showProfile"
                                class="flex items-center p-1.5 rounded-xl hover:bg-primary-50 transition-all group">
                                <div class="relative">
                                    <img class="h-9 w-9 rounded-xl ring-2 ring-white shadow-md group-hover:ring-primary-200 transition-all"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=7950f2&color=fff&bold=true"
                                        alt="{{ auth()->user()->name }}">
                                    <div
                                        class="absolute -bottom-0.5 -right-0.5 h-3 w-3 bg-success-500 border-2 border-white rounded-full">
                                    </div>
                                </div>
                                <svg class="ml-2 h-4 w-4 text-gray-400 group-hover:text-primary-600 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
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
                                class="origin-top absolute right-0 mt-2 w-64 rounded-2xl shadow-2xl bg-white z-50 ring-1 ring-black/5 overflow-hidden">

                                <!-- Profile Header -->
                                <div class="bg-gradient-to-br from-primary-500 to-accent-500 p-4">
                                    <div class="flex items-center space-x-3">
                                        <img class="h-12 w-12 rounded-xl ring-2 ring-white/50"
                                            src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=ffffff&color=7950f2&bold=true"
                                            alt="{{ auth()->user()->name }}">
                                        <div>
                                            <p class="text-white font-semibold">{{ auth()->user()->name }}</p>
                                            <p class="text-white/80 text-xs">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-2">
                                    <a href="{{ route('profile.index') }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-primary-50 rounded-xl transition-all group">
                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        Profil Saya
                                    </a>

                                    @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="flex items-center px-3 py-2 text-sm text-gray-700 hover:bg-primary-50 rounded-xl transition-all group">
                                        <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-primary-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Admin Panel
                                    </a>
                                    @endif

                                    <div class="border-t border-gray-100 my-2"></div>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="flex items-center w-full px-3 py-2 text-sm text-gray-700 hover:bg-red-50 rounded-xl transition-all group">
                                            <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-red-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
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

        <!-- Main Content -->
        <main class="pb-20 lg:pb-0">
            @yield('content')
        </main>

        <!-- Modern Bottom Navigation (Mobile) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-white z-50 border-t border-gray-200/50 z-50 shadow-lg">
            <div class="grid grid-cols-5 h-16">
                <a href="{{ route('dashboard') }}" class="group flex flex-col items-center justify-center relative">
                    <div
                        class="@if(request()->routeIs('dashboard')) text-primary-600 @else text-gray-500 @endif transition-all">
                        <svg class="h-5 w-5 mb-0.5 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        <span class="text-xs font-medium">Home</span>
                    </div>
                    @if(request()->routeIs('dashboard'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full">
                    </div>
                    @endif
                </a>

                <a href="{{ route('applications.index') }}"
                    class="group flex flex-col items-center justify-center relative">
                    <div
                        class="@if(request()->routeIs('applications.*')) text-primary-600 @else text-gray-500 @endif transition-all">
                        <svg class="h-5 w-5 mb-0.5 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        <span class="text-xs font-medium">Layanan</span>
                    </div>
                    @if(request()->routeIs('applications.*'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full">
                    </div>
                    @endif
                </a>

                <a href="{{ route('complaints.create') }}"
                    class="group flex flex-col items-center justify-center relative">
                    <div
                        class="bg-gradient-to-r from-primary-500 to-accent-500 p-3 rounded-xl shadow-lg transform group-hover:scale-110 transition-all -mt-8">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-gray-600 mt-1">Lapor</span>
                </a>

                <a href="{{ route('news.index') }}" class="group flex flex-col items-center justify-center relative">
                    <div
                        class="@if(request()->routeIs('news.*')) text-primary-600 @else text-gray-500 @endif transition-all">
                        <svg class="h-5 w-5 mb-0.5 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15">
                            </path>
                        </svg>
                        <span class="text-xs font-medium">Berita</span>
                    </div>
                    @if(request()->routeIs('news.*'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full">
                    </div>
                    @endif
                </a>

                <a href="{{ route('profile.index') }}" class="group flex flex-col items-center justify-center relative">
                    <div
                        class="@if(request()->routeIs('profile.*')) text-primary-600 @else text-gray-500 @endif transition-all">
                        <svg class="h-5 w-5 mb-0.5 group-hover:scale-110 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-xs font-medium">Profil</span>
                    </div>
                    @if(request()->routeIs('profile.*'))
                    <div
                        class="absolute top-0 left-1/2 -translate-x-1/2 w-12 h-0.5 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full">
                    </div>
                    @endif
                </a>
            </div>
        </nav>
    </div>

    <!-- Modern Toast Notifications -->
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed bottom-24 lg:bottom-8 right-4 z-50 max-w-sm">
        <div class="bg-white z-50 rounded-2xl shadow-2xl border border-success-100 p-4 flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-br from-success-400 to-teal-400 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Berhasil!</p>
                <p class="text-sm text-gray-500 mt-1">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-4"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed bottom-24 lg:bottom-8 right-4 z-50 max-w-sm">
        <div class="bg-white z-50 rounded-2xl shadow-2xl border border-red-100 p-4 flex items-start space-x-3">
            <div class="flex-shrink-0">
                <div
                    class="h-10 w-10 rounded-full bg-gradient-to-br from-red-400 to-pink-400 flex items-center justify-center">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900">Error!</p>
                <p class="text-sm text-gray-500 mt-1">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @stack('scripts')
</body>

</html>
