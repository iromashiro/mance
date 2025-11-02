@extends('layouts.app')

@section('title', 'Layanan')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Layanan Digital
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Akses berbagai layanan pemerintah Kabupaten Muara Enim secara digital
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <span class="text-sm text-gray-500">
                        {{ $applications->total() }} layanan tersedia
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        {{-- Filters Section --}}
        <div class="mb-8" x-data="{ showFilters: false }">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                {{-- Search Bar --}}
                <div class="flex-1 max-w-lg">
                    <form method="GET" action="{{ route('applications.index') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..."
                            class="w-full px-4 py-2 pl-10 pr-4 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>
                </div>

                {{-- Filter Toggle --}}
                <button @click="showFilters = !showFilters"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                    @if(request('category') || request('favorites'))
                    <span class="ml-2 px-2 py-0.5 text-xs bg-primary-100 text-primary-700 rounded-full">
                        {{ (request('category') ? 1 : 0) + (request('favorites') ? 1 : 0) }}
                    </span>
                    @endif
                </button>
            </div>

            {{-- Filter Panel --}}
            <div x-show="showFilters" x-transition class="mt-4 p-4 bg-white rounded-lg border border-gray-200">
                <form method="GET" action="{{ route('applications.index') }}" class="space-y-4">
                    @if(request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach(['umum' => 'Umum', 'pelajar' => 'Pelajar', 'mahasiswa' => 'Mahasiswa', 'pekerja' =>
                            'Pekerja'] as $value => $label)
                            <label class="flex items-center">
                                <input type="radio" name="category" value="{{ $value }}"
                                    {{ request('category') == $value ? 'checked' : '' }}
                                    class="mr-2 text-primary-600 focus:ring-primary-500">
                                <span class="text-sm text-gray-700">{{ $label }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    @auth
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="favorites" value="1"
                                {{ request('favorites') ? 'checked' : '' }}
                                class="mr-2 text-primary-600 rounded focus:ring-primary-500">
                            <span class="text-sm font-medium text-gray-700">Tampilkan favorit saja</span>
                        </label>
                    </div>
                    @endauth

                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            Terapkan Filter
                        </button>
                        @if(request('category') || request('favorites'))
                        <a href="{{ route('applications.index') }}"
                            class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors">
                            Reset
                        </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Applications Grid --}}
        @if($applications->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($applications as $app)
            <div
                class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300">
                {{-- Image/Icon --}}
                <div class="aspect-video bg-gradient-to-br from-gray-50 to-gray-100 relative overflow-hidden">
                    @if($app->icon_url)
                    <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}"
                        class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <div class="w-20 h-20 bg-white rounded-2xl shadow-sm flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                    </div>
                    @endif

                    {{-- Category Badge --}}
                    @if($app->categories->isNotEmpty())
                    <div class="absolute top-2 left-2">
                        <span
                            class="px-2.5 py-1 bg-white/90 backdrop-blur text-xs font-medium text-gray-700 rounded-lg">
                            {{ $app->categories->first()->name }}
                        </span>
                    </div>
                    @endif

                    {{-- Favorite Button --}}
                    @auth
                    <button type="button" onclick="toggleFavorite({{ $app->id }})"
                        class="absolute top-2 right-2 p-2 bg-white/90 backdrop-blur rounded-lg hover:bg-white transition-colors">
                        <svg id="fav-icon-{{ $app->id }}"
                            class="w-4 h-4 {{ auth()->user()->favorites()->where('application_id', $app->id)->exists() ? 'text-red-500 fill-current' : 'text-gray-400' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                    @endauth
                </div>

                {{-- Content --}}
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                        {{ $app->name }}
                    </h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                        {{ $app->description }}
                    </p>

                    {{-- Stats --}}
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        <span class="flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ number_format($app->views_count ?? 0) }}
                        </span>
                        @if($app->favorites_count > 0)
                        <span class="flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ number_format($app->favorites_count) }}
                        </span>
                        @endif
                    </div>

                    {{-- Action Button --}}
                    <a href="{{ route('applications.show', $app) }}" onclick="trackAppClick({{ $app->id }})"
                        class="inline-flex items-center justify-center w-full px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        Buka Layanan
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($applications->hasPages())
        <div class="mt-12">
            {{ $applications->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="text-center py-16 px-4 bg-white rounded-xl border border-gray-200">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada layanan ditemukan</h3>
            <p class="text-sm text-gray-500 mb-6">
                @if(request('search'))
                Coba ubah kata kunci pencarian Anda
                @else
                Belum ada layanan yang tersedia saat ini
                @endif
            </p>
            @if(request()->hasAny(['search', 'category', 'favorites']))
            <a href="{{ route('applications.index') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                Reset Filter
            </a>
            @endif
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .line-clamp-3 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleFavorite(appId) {
        fetch(`/api/applications/${appId}/favorite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            const icon = document.getElementById(`fav-icon-${appId}`);
            if (data.favorited) {
                icon.classList.add('text-red-500', 'fill-current');
                icon.classList.remove('text-gray-400');
            } else {
                icon.classList.remove('text-red-500', 'fill-current');
                icon.classList.add('text-gray-400');
            }
        });
    }

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