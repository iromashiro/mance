@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- ===== FULL-BLEED HERO (edge to edge, nempel header) ===== --}}
{{-- Ditaruh DI LUAR container padding supaya tidak ada gap di atas --}}
<section class="relative left-1/2 right-1/2 -mx-[50vw] w-screen -mt-px">
    <div class="relative bg-gray-900 text-white shadow-xl">
        {{-- gambar latar --}}
        <img src="{{ asset('banner.webp') }}" alt="Muara Enim"
            class="absolute inset-0 w-full h-44 sm:h-56 object-cover" />

        {{-- overlay biar teks kebaca --}}
        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/40 via-black/10 to-transparent">
        </div>

        {{-- konten (padding dalam supaya sejajar dengan konten lain) --}}
        <div class="relative px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-2 mb-3">
                <span class="h-2 w-2 rounded-full bg-accent-400"></span>
                <span class="text-xs/5 tracking-wide uppercase text-white/80">Selamat Datang</span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold leading-tight">
                Selamat Datang, {{ Str::of(Auth::user()->name)->words(2, '') }}!
            </h2>
            <p class="mt-2 text-white/90 text-sm sm:text-base">
                Wujudkan Muara Enim, <span class="font-semibold">MEMBARA!</span>
            </p>
        </div>
    </div>
</section>

{{-- ===== KONTEN HALAMAN (baru masuk container dengan padding) ===== --}}
<div class="px-4 pt-6 pb-0 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- =========================
             CUACA & KUALITAS UDARA (COMPACT + CLICKABLE)
             ========================= --}}
    <button type="button" @click="$dispatch('open-weather-modal')" id="wx-card-compact"
        class="w-full relative overflow-hidden rounded-3xl bg-gradient-to-br from-blue-500 via-blue-600 to-pink-600 shadow-2xl mb-6 hover:shadow-3xl transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
        <!-- glow -->
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-60 h-60 rounded-full blur-3xl"></div>
        </div>

        <!-- Glassmorphism overlay -->
        <div class="absolute inset-0 bg-white/5 backdrop-blur-sm"></div>

        <div class="relative p-6 text-white">
            <!-- Loading State -->
            <div id="wx-loading-compact" class="flex items-center justify-center text-white/90 py-4">
                <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>
                    <path class="opacity-75" stroke-width="4" d="M4 12a8 8 0 018-8"></path>
                </svg>
                <span class="text-sm">Memuat informasi cuaca...</span>
            </div>

            <!-- Compact Content -->
            <div id="wx-content-compact" class="hidden">
                <div class="flex items-center justify-between">
                    <!-- Weather Info -->
                    <div class="flex items-center space-x-4">
                        <div id="wx-icon-compact" class="text-6xl">⛅</div>
                        <div>
                            <p class="text-white/80 text-sm mb-1">Suhu Saat Ini</p>
                            <div class="flex items-end">
                                <span id="wx-temp-compact" class="text-4xl font-extrabold leading-none">--</span>
                                <span class="ml-1 text-xl font-semibold">°C</span>
                            </div>
                            <p id="wx-desc-compact" class="text-white/90 text-sm mt-1">—</p>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="text-right space-y-2">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2 border border-white/20">
                            <p class="text-white/70 text-xs">Terasa</p>
                            <p id="wx-feels-compact" class="text-white font-semibold">—°C</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl px-3 py-2 border border-white/20">
                            <p class="text-white/70 text-xs">AQI</p>
                            <p id="aqi-value-compact" class="text-white font-semibold">--</p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Info -->
                <div class="mt-4 flex items-center justify-between text-xs text-white/70">
                    <div class="flex items-center space-x-4">
                        <span>💧 <span id="wx-hum-compact">—%</span></span>
                        <span>☀️ UV <span id="wx-uv-compact">—</span></span>
                    </div>
                    <span class="flex items-center">
                        Tap untuk detail lengkap
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </button>

    {{-- =========================
             WEATHER DETAIL MODAL (JAKI-STYLE PREMIUM)
             ========================= --}}
    <div x-data="{ open: false }" @open-weather-modal.window="open = true" @keydown.escape.window="open = false"
        x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-900/80 backdrop-blur-sm" @click="open = false" x-transition.opacity></div>

        <!-- Modal -->
        <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden max-h-[85vh] flex flex-col"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95">

            <!-- Header -->
            <div class="bg-purple-600 px-6 py-4 flex items-center justify-between">
                <h3 class="text-base font-bold text-white flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                    Informasi Cuaca & Kualitas Udara
                </h3>
                <button type="button" class="p-2 hover:bg-white/10 rounded-lg transition-colors" @click="open = false">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 bg-white">
                <!-- Loading State -->
                <div id="wx-modal-loading" class="flex items-center justify-center py-12 text-gray-500">
                    <svg class="animate-spin h-6 w-6 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>
                        <path class="opacity-75" stroke-width="4" d="M4 12a8 8 0 018-8"></path>
                    </svg>
                    Memuat data...
                </div>

                <!-- Detail Content -->
                <div id="wx-modal-content" class="hidden space-y-4">
                    <!-- Current Weather Card -->
                    <div class="bg-gray-100 rounded-3xl p-6">
                        <div class="flex items-center text-gray-700 text-sm font-medium mb-6">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                            <span>Kondisi Cuaca Saat Ini</span>
                        </div>

                        <div class="flex items-start justify-between mb-6">
                            <div class="flex-1">
                                <div class="flex items-baseline mb-2">
                                    <span id="wx-temp-modal"
                                        class="text-7xl font-black text-gray-900 leading-none tracking-tighter">--</span>
                                    <span class="text-3xl font-bold text-gray-700 ml-1">°C</span>
                                </div>
                                <p id="wx-desc-modal" class="text-gray-700 text-base font-medium">—</p>
                            </div>
                            <div class="text-right">
                                <div id="wx-icon-modal" class="text-8xl mb-2">⛅</div>
                                <p id="wx-feels-modal" class="text-sm text-gray-600 font-medium">Terasa: —°C</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white rounded-2xl p-4">
                                <p class="text-sm text-gray-600 mb-1">Kelembapan</p>
                                <p id="wx-hum-modal" class="text-3xl font-black text-gray-900">—%</p>
                            </div>
                            <div class="bg-white rounded-2xl p-4">
                                <p class="text-sm text-gray-600 mb-1">Indeks UV</p>
                                <p id="wx-uv-modal" class="text-3xl font-black text-gray-900">—</p>
                            </div>
                        </div>
                    </div>

                    <!-- Air Quality Card -->
                    <div class="bg-green-100 rounded-3xl p-6">
                        <div class="flex items-center text-gray-700 text-sm font-medium mb-6">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kualitas Udara (AQI)</span>
                        </div>

                        <div class="flex items-start justify-between mb-5">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-4">
                                    <span id="aqi-value-modal"
                                        class="text-7xl font-black text-gray-900 leading-none tracking-tighter">--</span>
                                    <span id="aqi-chip-modal"
                                        class="px-4 py-2 rounded-full text-sm font-bold bg-yellow-400 text-gray-900">—</span>
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-700">
                                    <span>Polutan Dominan:</span>
                                    <span id="aqi-dominant-modal" class="font-bold text-gray-900">—</span>
                                </div>
                            </div>
                            <div class="flex items-center pt-3">
                                <span class="h-5 w-5 rounded-full ring-4 ring-white shadow-md" id="aqi-dot-modal"
                                    style="background:#9ca3af"></span>
                            </div>
                        </div>

                        <!-- AQI Bar -->
                        <div class="mt-6">
                            <div class="h-2 w-full bg-gray-200 rounded-full overflow-hidden">
                                <div id="aqi-bar-modal" class="h-2 w-0 rounded-full transition-all duration-500"
                                    style="background:linear-gradient(90deg,#22c55e,#eab308,#f97316,#ef4444,#8b5cf6,#7f1d1d);">
                                </div>
                            </div>
                            <div class="grid grid-cols-6 gap-0.5 mt-3 text-[10px] text-gray-600 font-medium">
                                <div class="text-center">
                                    <div class="font-bold text-gray-900 mb-0.5">0</div>
                                    <div>Baik</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-bold text-gray-900 mb-0.5">50</div>
                                    <div>Sedang</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-bold text-gray-900 mb-0.5">100</div>
                                    <div>Tidak<br>Sehat</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-bold text-gray-900 mb-0.5">150</div>
                                    <div>Sangat<br>Tidak<br>Sehat</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-bold text-gray-900 mb-0.5">200</div>
                                    <div>Berbahaya</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-bold text-gray-900 mb-0.5">300+</div>
                                    <div>Bahaya</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
             QUICK ACTIONS
             ========================= --}}
    <div class="mb-8">
        <h2 class="text-xl font-heading font-bold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- ... (card aksi cepat kamu—tidak diubah) ... --}}
            <a href="{{ route('complaints.create') }}"
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 p-6 hover:shadow-xl transition-all hover:-translate-y-1">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-primary-200/30 rounded-full blur-2xl group-hover:scale-150 transition-transform">
                </div>
                <div class="relative">
                    <div
                        class="h-12 w-12 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Buat Laporan</h3>
                    <p class="text-xs text-gray-600 mt-1">Laporkan masalah baru</p>
                </div>
            </a>

            <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-services'))"
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-success-50 to-success-100 p-6 hover:shadow-xl transition-all hover:-translate-y-1">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-success-200/30 rounded-full blur-2xl group-hover:scale-150 transition-transform">
                </div>
                <div class="relative">
                    <div
                        class="h-12 w-12 bg-gradient-to-br from-success-500 to-teal-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Layanan</h3>
                    <p class="text-xs text-gray-600 mt-1">Akses layanan publik</p>
                </div>
            </a>

            <a href="{{ route('tours.index') }}"
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 p-6 hover:shadow-xl transition-all hover:-translate-y-1">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-blue-200/30 rounded-full blur-2xl group-hover:scale-150 transition-transform">
                </div>
                <div class="relative">
                    <div
                        class="h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <!-- Pin wisata / destinasi -->
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 21s-7-6.273-7-12a7 7 0 1 1 14 0c0 5.727-7 12-7 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Wisata</h3>
                    <p class="text-xs text-gray-600 mt-1">Panduan Wisata</p>
                </div>
            </a>

            <a href="{{ route('profile.index') }}"
                class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-accent-50 to-pink-100 p-6 hover:shadow-xl transition-all hover:-translate-y-1">
                <div
                    class="absolute top-0 right-0 w-20 h-20 bg-accent-200/30 rounded-full blur-2xl group-hover:scale-150 transition-transform">
                </div>
                <div class="relative">
                    <div
                        class="h-12 w-12 bg-gradient-to-br from-accent-500 to-pink-600 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900">Profil</h3>
                    <p class="text-xs text-gray-600 mt-1">Kelola akun Anda</p>
                </div>
            </a>
        </div>
    </div>{{-- =========================
             QUICK STATS
             ========================= --}}
    @php
    $stats = [
    'active_complaints' => Auth::user()->complaints()->whereIn('status', ['pending','process'])->count(),
    'completed_complaints'=> Auth::user()->complaints()->where('status','completed')->count(),
    'unread_notifications'=> Auth::user()->notifications()->whereNull('read_at')->count(),
    'total_applications' => \App\Models\Application::where('is_active', true)->count(),
    ];
    @endphp

    {{--
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Complaints -->
            <div
                class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-yellow-100/50 to-transparent rounded-full -translate-y-16 translate-x-16">
                </div>
                <div class="flex items-center justify-between relative">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Pengaduan Aktif</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_complaints'] }}</p>
    <div class="flex items-center mt-2 text-xs">
        <span class="text-yellow-600 font-medium">Sedang Diproses</span>
    </div>
</div>
<div
    class="h-14 w-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
    </svg>
</div>
</div>
</div>

<!-- Completed Complaints -->
<div
    class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 relative overflow-hidden group">
    <div
        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100/50 to-transparent rounded-full -translate-y-16 translate-x-16">
    </div>
    <div class="flex items-center justify-between relative">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Pengaduan Selesai</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['completed_complaints'] }}</p>
            <div class="flex items-center mt-2 text-xs">
                <svg class="h-3 w-3 text-success-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-success-600 font-medium">Terselesaikan</span>
            </div>
        </div>
        <div
            class="h-14 w-14 bg-gradient-to-br from-success-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
    </div>
</div>

<!-- Notifications -->
<div
    class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 relative overflow-hidden group">
    <div
        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-100/50 to-transparent rounded-full -translate-y-16 translate-x-16">
    </div>
    <div class="flex items-center justify-between relative">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Notifikasi Baru</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['unread_notifications'] }}</p>
            <div class="flex items-center mt-2 text-xs">
                @if($stats['unread_notifications'] > 0)
                <span class="text-primary-600 font-medium animate-pulse">Belum Dibaca</span>
                @else
                <span class="text-gray-500">Semua Terbaca</span>
                @endif
            </div>
        </div>
        <div
            class="h-14 w-14 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
        </div>
    </div>
</div>

<!-- Total Services -->
<div
    class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 relative overflow-hidden group">
    <div
        class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-pink-100/50 to-transparent rounded-full -translate-y-16 translate-x-16">
    </div>
    <div class="flex items-center justify-between relative">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wider text-gray-500 mb-1">Total Layanan</p>
            <p class="text-3xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
            <div class="flex items-center mt-2 text-xs">
                <span class="text-accent-600 font-medium">Tersedia</span>
            </div>
        </div>
        <div
            class="h-14 w-14 bg-gradient-to-br from-accent-400 to-pink-500 rounded-2xl flex items-center justify-center shadow-lg transform group-hover:scale-110 transition-transform">
            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
        </div>
    </div>
</div>
</div>
--}}

{{-- =========================
             REKOMENDASI UNTUK ANDA
             ========================= --}}
@if($recommendedApps->isNotEmpty() || $recommendedNews->isNotEmpty())
<div class="mb-8">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-heading font-bold text-gray-900">Rekomendasi Untuk Anda</h2>
            <p class="text-sm text-gray-600 mt-1">Berdasarkan kategori: <span
                    class="font-semibold text-primary-600">{{ ucfirst(auth()->user()->category ?? 'Umum') }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recommended Applications --}}
        @if($recommendedApps->isNotEmpty())
        <div
            class="bg-gradient-to-br from-primary-50 to-accent-50 rounded-2xl shadow-xl overflow-hidden border border-primary-100">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <span class="inline-flex items-center justify-center w-8 h-8 bg-primary-600 rounded-lg mr-2">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                </path>
                            </svg>
                        </span>
                        Layanan Rekomendasi
                    </h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($recommendedApps->take(4) as $app)
                    <a href="{{ route('applications.show', $app) }}"
                        class="group bg-white rounded-xl p-4 hover:shadow-lg transition-all hover:-translate-y-1">
                        <div class="flex flex-col items-center text-center">
                            @if($app->icon_path)
                            <img src="{{ Storage::url($app->icon_path) }}" alt="{{ $app->name }}"
                                class="w-12 h-12 rounded-xl mb-2 group-hover:scale-110 transition-transform">
                            @else
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-primary-400 to-accent-400 rounded-xl flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                    </path>
                                </svg>
                            </div>
                            @endif
                            <h4 class="text-sm font-semibold text-gray-900 line-clamp-2">{{ $app->name }}</h4>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endif


{{-- =========================
             RECENT SECTIONS
             ========================= --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Complaints --}}
    <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-heading font-bold text-gray-900">Pengaduan Terbaru</h2>
                <a href="{{ route('complaints.index') }}"
                    class="text-sm font-medium text-primary-600 hover:text-primary-700 flex items-center">
                    Lihat Semua
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @php $recentComplaints = Auth::user()->complaints()->latest()->take(4)->get(); @endphp

            @if($recentComplaints->count() > 0)
            <div class="space-y-4">
                @foreach($recentComplaints as $index => $complaint)
                <div class="relative flex items-start space-x-4 group">
                    @if(!$loop->last)
                    <div class="absolute left-5 top-10 bottom-0 w-0.5 bg-gray-200"></div>
                    @endif

                    <div class="relative flex-shrink-0">
                        <div @class([ 'h-10 w-10 rounded-full flex items-center justify-center ring-4 ring-white shadow-md'
                            , 'bg-gradient-to-br from-success-400 to-teal-500'=> $complaint->status === 'completed',
                            'bg-gradient-to-br from-yellow-400 to-orange-500' => $complaint->status === 'process',
                            'bg-gradient-to-br from-red-400 to-pink-500' => $complaint->status === 'rejected',
                            'bg-gradient-to-br from-gray-400 to-gray-500' => !in_array($complaint->status,
                            ['completed','process','rejected']),
                            ])>
                            @if($complaint->status === 'completed')
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            @elseif($complaint->status === 'process')
                            <svg class="h-5 w-5 text-white animate-spin" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @else
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 min-w-0">
                        <a href="{{ route('complaints.show', $complaint) }}"
                            class="group-hover:text-primary-600 transition-colors">
                            <h4 class="text-sm font-semibold text-gray-900">{{ $complaint->ticket_number }}</h4>
                        </a>
                        <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $complaint->description }}</p>
                        <div class="flex items-center space-x-4 mt-2">
                            <span @class([ 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium'
                                , 'bg-success-100 text-success-800'=> $complaint->status === 'completed',
                                'bg-yellow-100 text-yellow-800' => $complaint->status === 'process',
                                'bg-red-100 text-red-800' => $complaint->status === 'rejected',
                                'bg-gray-100 text-gray-800' => !in_array($complaint->status,
                                ['completed','process','rejected']),
                                ])>{{ ucfirst($complaint->status) }}</span>
                            <span class="text-xs text-gray-500">{{ $complaint->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-3">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
                <p class="text-gray-500">Belum ada pengaduan</p>
                <a href="{{ route('complaints.create') }}"
                    class="inline-flex items-center px-6 py-3 font-semibold rounded-xl shadow-lg bg-gradient-to-r from-blue-500 to-blue-700 text-white hover:from-blue-600 hover:to-blue-800 transform transition-all duration-300 hover:scale-105 hover:shadow-xl active:scale-95 focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500/50 mt-4">
                    Buat Pengaduan Pertama
                </a>
            </div>
            @endif
        </div>
    </div>

    {{-- Recent News --}}
    <div class="bg-white rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-heading font-bold text-gray-900">Berita Terkini</h2>
                <a href="{{ route('news.index') }}"
                    class="text-sm font-medium text-primary-600 hover:text-primary-700 flex items-center">
                    Lihat Semua
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            @if($recentNews->count() > 0)
            <div class="space-y-4">
                @foreach($recentNews as $news)
                <a href="{{ route('news.show', $news->id) }}"
                    class="group block rounded-xl hover:bg-gradient-to-r hover:from-gray-50 hover:to-primary-50 p-3 -m-3 transition-all">
                    <div class="flex space-x-4">
                        @if($news->image_url)
                        <img src="{{ \Illuminate\Support\Str::startsWith($news->image_url, ['http://','https://']) ? $news->image_url : Storage::url($news->image_url) }}"
                            alt="{{ $news->title }}"
                            class="h-20 w-20 object-cover rounded-xl shadow-md group-hover:shadow-lg transition-shadow">
                        @else
                        <div
                            class="h-20 w-20 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                            <svg class="h-8 w-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3
                                class="text-sm font-semibold text-gray-900 group-hover:text-primary-700 transition-colors line-clamp-2">
                                {{ $news->title }}</h3>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $news->excerpt }}</p>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="inline-flex items-center text-xs text-gray-500">
                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $news->published_at->diffForHumans() }}
                                </span>
                                <span class="inline-flex items-center text-xs text-gray-500">
                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ method_exists($news, 'views') ? $news->views()->count() : (int) ($news->views ?? 0) }}
                                    views
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-3">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <p class="text-gray-500">Belum ada berita terbaru</p>
            </div>
            @endif
        </div>
    </div>
</div>
</div>

<!-- Services Modal -->
<div x-data="{ open: false, loading: true, src: '{{ route('applications.index', ['embed' => 1]) }}' }"
    x-on:open-services.window="open = true; loading = true" x-on:keydown.escape.window="open = false">
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/70 backdrop-blur-sm" @click="open = false" x-transition.opacity></div>
        <div class="relative w-[95vw] sm:w-[90vw] lg:w-[80vw] h-[85dvh] lg:h-[80dvh] mx-auto my-0 px-0">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden ring-1 ring-gray-200">
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-900">Layanan</h3>
                    <button type="button" class="p-2 text-gray-400 hover:text-gray-600" @click="open = false"
                        aria-label="Tutup">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="relative w-full h-[calc(80vh-3rem)]">
                    <div x-show="loading" x-cloak x-transition.opacity
                        class="absolute inset-0 flex items-center justify-center bg-white">
                        <svg class="animate-spin h-8 w-8 text-primary-500" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        <span class="ml-3 text-sm text-gray-600">Memuat...</span>
                    </div>
                    <iframe :src="src" class="w-full h-full border-0" loading="lazy" @load="loading = false"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>
@endpush

@push('scripts')
<script>
    (() => {
      const DEFAULT_COORD = { lat: -3.731, lng: 103.835 };

      // ==== helpers ====
      const $ = id => document.getElementById(id);

      // UI Elements (Compact + Modal)
      const ui = {
        // Compact card
        loadCompact: $('wx-loading-compact'),
        contentCompact: $('wx-content-compact'),
        tempCompact: $('wx-temp-compact'),
        descCompact: $('wx-desc-compact'),
        iconCompact: $('wx-icon-compact'),
        feelsCompact: $('wx-feels-compact'),
        humCompact: $('wx-hum-compact'),
        uvCompact: $('wx-uv-compact'),
        aqiCompact: $('aqi-value-compact'),

        // Modal
        loadModal: $('wx-modal-loading'),
        contentModal: $('wx-modal-content'),
        tempModal: $('wx-temp-modal'),
        descModal: $('wx-desc-modal'),
        iconModal: $('wx-icon-modal'),
        feelsModal: $('wx-feels-modal'),
        humModal: $('wx-hum-modal'),
        uvModal: $('wx-uv-modal'),
        aqiModal: $('aqi-value-modal'),
        aqiChipModal: $('aqi-chip-modal'),
        aqiDomModal: $('aqi-dominant-modal'),
        aqiBarModal: $('aqi-bar-modal'),
        aqiDotModal: $('aqi-dot-modal')
      };

      const icon = t => {
        t = (t||'').toLowerCase();
        if (t.includes('rain')||t.includes('hujan')) return '🌧️';
        if (t.includes('thunder')) return '⛈️';
        if (t.includes('snow')) return '❄️';
        if (t.includes('wind')) return '🌬️';
        if (t.includes('cloud')||t.includes('berawan')) return '☁️';
        if (t.includes('sun')||t.includes('clear')||t.includes('cerah')) return '☀️';
        return '⛅';
      };

      const aqiColor = (v) => v==null?'#9ca3af':
        v<=50?'#22c55e':v<=100?'#eab308':v<=150?'#f97316':v<=200?'#ef4444':v<=300?'#8b5cf6':'#7f1d1d';

      const aqiLabel = (v) => {
        if (v==null) return '—';
        if (v<=50) return 'Baik';
        if (v<=100) return 'Sedang';
        if (v<=150) return 'Tidak Sehat';
        if (v<=200) return 'Sangat Tidak Sehat';
        if (v<=300) return 'Berbahaya';
        return 'Bahaya';
      };

      // ==== API via backend ====
      async function getWeather(lat,lng){
        const r = await fetch(`/api/wx/weather?lat=${lat}&lng=${lng}`, { headers:{Accept:'application/json'} });
        if(!r.ok) throw new Error('weather '+r.status);
        return r.json();
      }

      async function getAir(lat,lng){
        const r = await fetch(`/api/wx/air?lat=${lat}&lng=${lng}`, { headers:{Accept:'application/json'} });
        if(!r.ok) throw new Error('air '+r.status);
        return r.json();
      }

      // ==== renderers ====
      function renderWeather(data){
        const c = data.currentConditions || {};
        const temp  = (c.temperature?.degrees ?? c.temperature) ?? null;
        const feels = (c.feelsLikeTemperature?.degrees ?? c.temperatureApparent) ?? null;
        let hum = (c.relativeHumidity ?? c.humidity ?? null);
        if (hum!=null && hum<=1) hum = hum*100;
        const uv = c.uvIndex ?? null;
        const txt = c.weatherCondition?.description?.text ?? c.weatherCondition?.text ?? c.weatherText ?? 'Kondisi terkini';
        const weatherIcon = icon(txt);

        // Update Compact Card
        if(ui.tempCompact) ui.tempCompact.textContent = temp!=null?Math.round(temp):'—';
        if(ui.descCompact) ui.descCompact.textContent = txt;
        if(ui.iconCompact) ui.iconCompact.textContent = weatherIcon;
        if(ui.feelsCompact) ui.feelsCompact.textContent = feels!=null?`${Math.round(feels)}°C`:'—°C';
        if(ui.humCompact) ui.humCompact.textContent = hum!=null?`${Math.round(hum)}%`:'—%';
        if(ui.uvCompact) ui.uvCompact.textContent = uv ?? '—';

        // Update Modal
        if(ui.tempModal) ui.tempModal.textContent = temp!=null?Math.round(temp):'—';
        if(ui.descModal) ui.descModal.textContent = txt;
        if(ui.iconModal) ui.iconModal.textContent = weatherIcon;
        if(ui.feelsModal) ui.feelsModal.textContent = `Terasa: ${feels!=null?Math.round(feels):'—'}°C`;
        if(ui.humModal) ui.humModal.textContent = hum!=null?`${Math.round(hum)}%`:'—%';
        if(ui.uvModal) ui.uvModal.textContent = uv ?? '—';
      }

      function renderAir(data){
        const idx = (data.indexes && data.indexes[0]) || {};
        const aqi = idx.aqi ?? idx.value ?? null;
        const label = aqiLabel(aqi);
        const dom = idx.dominantPollutant ?? '—';
        const color = aqiColor(aqi);

        // Update Compact Card
        if(ui.aqiCompact) ui.aqiCompact.textContent = aqi ?? '—';

        // Update Modal
        if(ui.aqiModal) ui.aqiModal.textContent = aqi ?? '—';
        if(ui.aqiChipModal) {
          ui.aqiChipModal.textContent = label;
          ui.aqiChipModal.style.backgroundColor = color + '33';
          ui.aqiChipModal.style.borderColor = color + '66';
          ui.aqiChipModal.style.color = color;
        }
        if(ui.aqiDomModal) ui.aqiDomModal.textContent = dom;

        const pct = Math.max(0, Math.min(1, (aqi ?? 0) / 300));
        if(ui.aqiBarModal) ui.aqiBarModal.style.width = `${pct*100}%`;
        if(ui.aqiDotModal) ui.aqiDotModal.style.backgroundColor = color;
      }

      // ==== geolokasi device ====
      function locate(){
        return new Promise(resolve => {
          if (!navigator.geolocation) return resolve({ ...DEFAULT_COORD });
          navigator.geolocation.getCurrentPosition(
            pos => resolve({ lat: pos.coords.latitude, lng: pos.coords.longitude }),
            ()  => resolve({ ...DEFAULT_COORD }),
            { enableHighAccuracy:true, timeout:8000, maximumAge:300000 }
          );
        });
      }

      async function loadAll(coord){
        // Show loading states
        if(ui.loadCompact) ui.loadCompact.classList.remove('hidden');
        if(ui.contentCompact) ui.contentCompact.classList.add('hidden');
        if(ui.loadModal) ui.loadModal.classList.remove('hidden');
        if(ui.contentModal) ui.contentModal.classList.add('hidden');

        try{
          const [w,a] = await Promise.all([
            getWeather(coord.lat,coord.lng),
            getAir(coord.lat,coord.lng)
          ]);

          renderWeather(w);
          renderAir(a);

          // Hide loading, show content
          if(ui.loadCompact) ui.loadCompact.classList.add('hidden');
          if(ui.contentCompact) ui.contentCompact.classList.remove('hidden');
          if(ui.loadModal) ui.loadModal.classList.add('hidden');
          if(ui.contentModal) ui.contentModal.classList.remove('hidden');
        }catch(e){
          console.error(e);
          if(ui.loadCompact) ui.loadCompact.innerHTML = '<span class="text-sm text-white/80">Gagal memuat data cuaca</span>';
          if(ui.loadModal) ui.loadModal.innerHTML = '<span class="text-sm text-gray-500">Gagal memuat data cuaca</span>';
        }
      }

      // Init on page load
      (async () => {
        const c = await locate();
        loadAll(c);
      })();
    })();
</script>
@endpush
@endsection