@extends('layouts.app')

@section('title', 'Layanan Publik')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Hero Section --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-accent-600 to-pink-600 shadow-2xl mb-8">
        <!-- Animated Background -->
        <div class="absolute inset-0">
            <div class="absolute top-0 -left-4 w-72 h-72 bg-white/20 rounded-full blur-3xl animate-float"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary-400/20 rounded-full blur-3xl animate-pulse-slow">
            </div>
        </div>

        <div class="relative p-8 lg:p-12">
            <div class="max-w-3xl">
                <h1 class="text-3xl lg:text-4xl font-heading font-bold text-white mb-4 animate-slide-up">
                    Layanan Publik Digital 🚀
                </h1>
                <p class="text-lg text-white/90 mb-6">
                    Akses berbagai layanan pemerintah Kabupaten Muara Enim dengan mudah, cepat, dan transparan.
                    Semua layanan dalam satu platform terintegrasi.
                </p>

                <!-- Search Bar -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <form action="{{ route('applications.index') }}" method="GET" class="relative">
                            @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari layanan yang Anda butuhkan..."
                                class="w-full px-5 py-3 pr-12 rounded-xl bg-white/95 backdrop-blur-sm text-gray-900 placeholder-gray-500 border-0 focus:ring-4 focus:ring-white/30 shadow-lg">
                            <button type="submit"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-600 hover:text-primary-700">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories Filter with Gradient Pills --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900">Filter Kategori</h2>
            <div class="text-sm text-gray-500">
                {{ $applications->total() }} layanan tersedia
            </div>
        </div>

        <!-- Mobile Dropdown -->
        <div class="sm:hidden">
            <select onchange="window.location.href = '?category=' + this.value" class="form-select w-full">
                <option value="" {{ !request('category') ? 'selected' : '' }}>🎯 Semua Kategori</option>
                @foreach(\App\Models\Category::withCount('applications')->get() as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }} ({{ $cat->applications_count }})
                </option>
                @endforeach
            </select>
        </div>

        <!-- Desktop Pills -->
        <div class="hidden sm:flex flex-wrap gap-2">
            <a href="{{ route('applications.index') }}"
                class="@if(!request('category')) bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow-lg @else bg-white text-gray-700 hover:bg-gray-50 @endif px-4 py-2 rounded-full font-medium text-sm transition-all hover:scale-105">
                🎯 Semua Kategori
            </a>
            @foreach(\App\Models\Category::withCount('applications')->get() as $cat)
            <a href="{{ route('applications.index', ['category' => $cat->slug]) }}"
                class="@if(request('category') == $cat->slug) bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow-lg @else bg-white text-gray-700 hover:bg-gray-50 @endif px-4 py-2 rounded-full font-medium text-sm transition-all hover:scale-105 flex items-center space-x-1">
                <span>{{ $cat->name }}</span>
                <span
                    class="@if(request('category') == $cat->slug) text-white/80 @else text-gray-400 @endif text-xs">({{ $cat->applications_count }})</span>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Personalized Recommendations --}}
    @if(Auth::user())
    @php
    $userCategory = Auth::user()->category;
    $recommendedApps = \App\Models\Application::whereHas('categories', function($q) use ($userCategory) {
    $q->where('slug', $userCategory);
    })->where('is_active', true)->take(3)->get();
    @endphp

    @if($recommendedApps->count() > 0)
    <div class="mb-8">
        <div class="flex items-center space-x-2 mb-4">
            <div
                class="h-8 w-8 rounded-lg bg-gradient-to-br from-accent-400 to-pink-500 flex items-center justify-center">
                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                </svg>
            </div>
            <h2 class="text-xl font-heading font-bold text-gray-900">Rekomendasi untuk Anda</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($recommendedApps as $app)
            <div class="relative group">
                <div
                    class="absolute inset-0 bg-gradient-to-r from-primary-400 to-accent-400 rounded-2xl blur opacity-25 group-hover:opacity-40 transition-opacity">
                </div>
                <div
                    class="relative bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        @if($app->icon_url)
                        <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}"
                            class="h-12 w-12 rounded-xl">
                        @else
                        <div
                            class="h-12 w-12 bg-gradient-to-br from-primary-400 to-accent-500 rounded-xl flex items-center justify-center">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        @endif
                        <span
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-accent-100 to-pink-100 text-accent-800">
                            ⭐ Rekomendasi
                        </span>
                    </div>

                    <h3 class="font-semibold text-gray-900 mb-2">{{ $app->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $app->description }}</p>

                    <a href="{{ $app->url }}" target="_blank" onclick="trackAppClick({{ $app->id }})"
                        class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700">
                        Akses Layanan
                        <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    {{-- Applications Grid with Modern Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($applications as $app)
        <div class="group relative" x-data="{ showModal: false }">
            <!-- Hover Glow Effect -->
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-primary-400 to-accent-400 rounded-2xl blur opacity-0 group-hover:opacity-30 transition-all duration-300">
            </div>

            <!-- Card Content -->
            <div
                class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                <!-- Category Badge -->
                @if($app->categories->first())
                <div class="absolute top-3 left-3 z-10">
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/90 backdrop-blur-sm text-gray-700 shadow-md">
                        {{ $app->categories->first()->name }}
                    </span>
                </div>
                @endif

                <!-- Favorite Button -->
                <div class="absolute top-3 right-3 z-10">
                    <form action="{{ route('applications.favorite', $app) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="p-2 rounded-lg bg-white/90 backdrop-blur-sm shadow-md hover:shadow-lg transition-all group/fav">
                            @if(Auth::user()->favorites()->where('application_id', $app->id)->exists())
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                            @else
                            <svg class="h-5 w-5 text-gray-400 group-hover/fav:text-red-400 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            @endif
                        </button>
                    </form>
                </div>

                <!-- Header with Icon/Image -->
                <div class="relative h-32 bg-gradient-to-br from-primary-100 via-accent-50 to-pink-100 p-6">
                    <div class="flex items-center justify-center h-full">
                        @if($app->icon_url)
                        <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}"
                            class="h-16 w-16 object-contain filter drop-shadow-lg">
                        @else
                        <div class="h-16 w-16 bg-white rounded-2xl shadow-lg flex items-center justify-center">
                            <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                                </path>
                            </svg>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                        {{ $app->name }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ $app->description }}
                    </p>

                    <!-- Stats -->
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            {{ number_format($app->views_count) }}
                        </span>
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            {{ number_format($app->favorites()->count()) }}
                        </span>
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            {{ number_format($app->users_count ?? rand(100, 1000)) }}
                        </span>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ $app->url }}" target="_blank" onclick="trackAppClick({{ $app->id }})"
                        class="btn btn-primary w-full justify-center group/btn">
                        <span>Akses Layanan</span>
                        <svg class="ml-2 h-4 w-4 group-hover/btn:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>

                    <button type="button" @click="showModal = true"
                        class="btn btn-secondary w-full mt-2 justify-center">
                        Aksi Cepat
                    </button>
                </div>
            </div>

            <!-- Quick Actions Modal -->
            <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="showModal=false"
                    x-transition.opacity></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4"
                    x-transition.scale.origin.center>
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $app->name }}</h3>
                                @if($app->categories->first())
                                <div class="mt-1 text-xs text-gray-500">{{ $app->categories->first()->name }}</div>
                                @endif
                            </div>
                            <button type="button" class="p-2 text-gray-400 hover:text-gray-600" @click="showModal=false"
                                aria-label="Tutup">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p class="mt-4 text-sm text-gray-600">{{ $app->description }}</p>

                        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <a href="{{ $app->url }}" target="_blank" onclick="trackAppClick({{ $app->id }})"
                                class="btn btn-primary justify-center">
                                Buka Layanan
                            </a>
                            <form action="{{ route('applications.favorite', $app) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary w-full justify-center">
                                    @if(Auth::check() && Auth::user()->favorites()->where('application_id',
                                    $app->id)->exists())
                                    Hapus Favorit
                                    @else
                                    Tambah Favorit
                                    @endif
                                </button>
                            </form>
                        </div>

                        <div class="mt-4 text-center">
                            <button type="button" class="text-sm text-gray-500 hover:text-gray-700"
                                @click="showModal=false">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- Empty State -->
        <div class="col-span-full">
            <div class="text-center py-16">
                <div
                    class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada layanan ditemukan</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    @if(request('search'))
                    Tidak ada layanan yang sesuai dengan pencarian "<span
                        class="font-medium">{{ request('search') }}</span>".
                    Coba gunakan kata kunci lain.
                    @else
                    Belum ada layanan yang tersedia di kategori ini.
                    @endif
                </p>
                @if(request('search') || request('category'))
                <a href="{{ route('applications.index') }}" class="btn btn-secondary">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Reset Filter
                </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    {{-- Pagination with Modern Style --}}
    @if($applications->hasPages())
    <div class="mt-12">
        {{ $applications->links() }}
    </div>
    @endif
</div>

@push('styles')
<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    [x-cloak] {
        display: none !important
    }
</style>
@endpush

@push('scripts')
<script>
    function trackAppClick(appId) {
        // Track user activity when clicking application
        fetch(`/api/applications/${appId}/track`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }
</script>
@endpush

@endsection
