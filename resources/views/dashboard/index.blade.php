@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- =========================
         CUACA & KUALITAS UDARA
         ========================= --}}
    <div id="wx-card"
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-primary-700 to-accent-600 shadow-2xl mb-8">
        <!-- glow -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-white rounded-full blur-3xl"></div>
        </div>

        <div class="relative p-8 lg:p-12 text-white">
            <!-- Loading -->
            <div id="wx-loading" class="flex items-center text-white/90">
                <svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>
                    <path class="opacity-75" stroke-width="4" d="M4 12a8 8 0 018-8"></path>
                </svg>
                Memuat kondisi cuaca dan kualitas udara…
            </div>

            <!-- Content -->
            <div id="wx-content" class="hidden grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Current weather -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/80 text-sm">Suhu Saat Ini</p>
                            <div class="flex items-end">
                                <span id="wx-temp" class="text-5xl font-extrabold leading-none">--</span>
                                <span class="ml-1 text-2xl font-semibold">°C</span>
                            </div>
                            <p id="wx-desc" class="text-white/90 mt-1">—</p>
                        </div>
                        <div class="text-right">
                            <div id="wx-icon" class="text-5xl">⛅</div>
                            <p id="wx-feels" class="text-white/80 text-sm mt-1">Terasa: —°C</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 mt-4 text-white/90 text-sm">
                        <div class="flex flex-col">
                            <span class="opacity-80">Kelembapan</span>
                            <span id="wx-hum">—%</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="opacity-80">UV</span>
                            <span id="wx-uv">—</span>
                        </div>
                    </div>
                </div>

                <!-- Air quality (no health list) -->
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-5 border border-white/20">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-white/80 text-sm">Kualitas Udara (AQI)</p>
                            <div class="mt-1 flex items-baseline space-x-3">
                                <span id="aqi-value" class="text-6xl font-extrabold leading-none">--</span>
                                <span id="aqi-chip"
                                    class="px-2 py-1 rounded-full text-xs font-semibold bg-white/10 border border-white/20">—</span>
                            </div>
                            <div class="mt-3 flex items-center space-x-2 text-sm">
                                <span class="text-white/80">Dominan:</span>
                                <span id="aqi-dominant" class="font-semibold">—</span>
                            </div>
                        </div>
                        <div class="hidden lg:flex items-center">
                            <span class="h-3 w-3 rounded-full ring-4 ring-white/30" id="aqi-dot"
                                style="background:#9ca3af"></span>
                        </div>
                    </div>

                    <div class="mt-5">
                        <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden">
                            <div id="aqi-bar" class="h-2 w-0 rounded-full transition-all duration-500"
                                style="background:linear-gradient(90deg,#22c55e,#eab308,#f97316,#ef4444,#8b5cf6,#7f1d1d);">
                            </div>
                        </div>
                        <div class="flex justify-between text-[10px] opacity-80 mt-1">
                            <span>0</span><span>50</span><span>100</span><span>150</span><span>200</span><span>300+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- =========================
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
         QUICK ACTIONS
         ========================= --}}
<div class="mb-8">
    <h2 class="text-xl font-heading font-bold text-gray-900 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
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

        <a href="{{ route('news.index') }}"
            class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 p-6 hover:shadow-xl transition-all hover:-translate-y-1">
            <div
                class="absolute top-0 right-0 w-20 h-20 bg-blue-200/30 rounded-full blur-2xl group-hover:scale-150 transition-transform">
            </div>
            <div class="relative">
                <div
                    class="h-12 w-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Berita</h3>
                <p class="text-xs text-gray-600 mt-1">Informasi terkini</p>
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
</div>

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

            @php
            $recentNews = \App\Models\News::published()->latest('published_at')->take(3)->get();
            @endphp

            @if($recentNews->count() > 0)
            <div class="space-y-4">
                @foreach($recentNews as $news)
                <a href="{{ route('news.show', $news->id) }}"
                    class="group block rounded-xl hover:bg-gradient-to-r hover:from-gray-50 hover:to-primary-50 p-3 -m-3 transition-all">
                    <div class="flex space-x-4">
                        @if($news->image_url)
                        <img src="{{ Storage::url($news->image_url) }}" alt="{{ $news->title }}"
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
                                    {{ $news->views()->count() }} views
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
  const ui = {
    load: $('wx-loading'), content: $('wx-content'),
    temp: $('wx-temp'), desc: $('wx-desc'), icon: $('wx-icon'),
    feels: $('wx-feels'), hum: $('wx-hum'), uv: $('wx-uv'),
    aqi: $('aqi-value'), aqiChip: $('aqi-chip'), aqiDom: $('aqi-dominant'),
    aqiBar: $('aqi-bar'), aqiDot: $('aqi-dot'),
    refresh: $('wx-refresh')
  };

  const fmtTime = ts => {
    try { return new Intl.DateTimeFormat('id-ID',{hour:'2-digit',minute:'2-digit'}).format(new Date(ts)); }
    catch { return '—'; }
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
    if (hum!=null && hum<=1) hum = hum*100; // 0–1 → %

    const uv = c.uvIndex ?? null;
    const txt = c.weatherCondition?.description?.text
             ?? c.weatherCondition?.text
             ?? c.weatherText
             ?? 'Kondisi terkini';

    ui.temp.textContent  = temp!=null?Math.round(temp):'—';
    ui.desc.textContent  = txt;
    ui.icon.textContent  = icon(txt);
    ui.feels.textContent = `Terasa: ${feels!=null?Math.round(feels):'—'}°C`;
    ui.hum.textContent   = hum!=null?`${Math.round(hum)}%`:'—';
    ui.uv.textContent    = uv ?? '—';
  }

  function renderAir(data){
    const idx = (data.indexes && data.indexes[0]) || {};
    const aqi = idx.aqi ?? idx.value ?? null;
    const label = idx.category ?? idx.displayName ?? '—';
    const dom = idx.dominantPollutant ?? '—';

    ui.aqi.textContent = aqi ?? '—';
    ui.aqiChip.textContent = label;
    const color = aqiColor(aqi);
    ui.aqiChip.style.backgroundColor = color + '33';
    ui.aqiChip.style.borderColor = color + '66';
    ui.aqiDom.textContent = dom;

    const pct = Math.max(0, Math.min(1, (aqi ?? 0) / 300));
    ui.aqiBar.style.width = `${pct*100}%`;
    ui.aqiDot.style.backgroundColor = color;
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
    ui.load.classList.remove('hidden');
    ui.content.classList.add('hidden');
    try{
      const [w,a] = await Promise.all([ getWeather(coord.lat,coord.lng), getAir(coord.lat,coord.lng) ]);
      renderWeather(w); renderAir(a);
      ui.load.classList.add('hidden');
      ui.content.classList.remove('hidden');
    }catch(e){
      console.error(e);
      ui.load.textContent = 'Gagal memuat data.';
    }
  }

  ui.refresh?.addEventListener('click', async () => { const c=await locate(); loadAll(c); });
  (async () => { const c=await locate(); loadAll(c); })();
})();
</script>
@endpush
@endsection
