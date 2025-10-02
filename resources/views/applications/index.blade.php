@extends('layouts.app')

@section('title', 'Layanan Publik')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Layanan Publik</h1>
        <p class="text-gray-600 mt-2">Akses berbagai layanan pemerintah Kabupaten Muara Enim</p>
    </div>

    <!-- Category Tabs -->
    <div class="mb-6">
        <div class="sm:hidden">
            <select onchange="window.location.href = '?category=' + this.value"
                class="block w-full rounded-md border-gray-300 focus:border-primary-500 focus:ring-primary-500">
                <option value="" {{ !request('category') ? 'selected' : '' }}>Semua Kategori</option>
                @foreach(\App\Models\Category::all() as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="hidden sm:block">
            <nav class="flex space-x-4" aria-label="Tabs">
                <a href="{{ route('applications.index') }}"
                    class="{{ !request('category') ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 font-medium text-sm rounded-md">
                    Semua Kategori
                </a>
                @foreach(\App\Models\Category::all() as $cat)
                <a href="{{ route('applications.index', ['category' => $cat->slug]) }}"
                    class="{{ request('category') == $cat->slug ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 font-medium text-sm rounded-md">
                    {{ $cat->name }}
                </a>
                @endforeach
            </nav>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="mb-6">
        <form action="{{ route('applications.index') }}" method="GET" class="flex gap-2">
            @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..."
                class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500">
            <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </button>
        </form>
    </div>

    <!-- Personalized Recommendations (based on user category) -->
    @if(Auth::user())
    @php
    $userCategory = Auth::user()->category;
    $recommendedApps = \App\Models\Application::whereHas('categories', function($q) use ($userCategory) {
    $q->where('slug', $userCategory);
    })->where('status', 'active')->take(4)->get();
    @endphp

    @if($recommendedApps->count() > 0)
    <div class="mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Rekomendasi untuk Anda</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($recommendedApps as $app)
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-lg p-4 border border-primary-200">
                <div class="flex items-center justify-between mb-3">
                    @if($app->icon_url)
                    <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}" class="h-10 w-10">
                    @else
                    <div class="h-10 w-10 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                            </path>
                        </svg>
                    </div>
                    @endif
                    <span class="text-xs font-medium text-primary-700 bg-white px-2 py-1 rounded">
                        Rekomendasi
                    </span>
                </div>
                <h3 class="font-semibold text-gray-900 mb-1">{{ $app->name }}</h3>
                <p class="text-sm text-gray-600 mb-3">{{ Str::limit($app->description, 50) }}</p>
                <a href="{{ $app->url }}" target="_blank"
                    class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700">
                    Akses Layanan
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    <!-- Applications Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($applications as $app)
        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
            <div class="p-6">
                <!-- Icon & Favorite -->
                <div class="flex items-start justify-between mb-4">
                    @if($app->icon_url)
                    <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}" class="h-12 w-12 rounded">
                    @else
                    <div
                        class="h-12 w-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                            </path>
                        </svg>
                    </div>
                    @endif

                    <!-- Favorite Button -->
                    <form action="{{ route('applications.favorite', $app) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                            @if(Auth::user()->favorites()->where('application_id', $app->id)->exists())
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                            @else
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                            @endif
                        </button>
                    </form>
                </div>

                <!-- App Info -->
                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $app->name }}</h3>
                <p class="text-sm text-gray-600 mb-4">{{ Str::limit($app->description, 80) }}</p>

                <!-- Categories -->
                <div class="flex flex-wrap gap-1 mb-4">
                    @foreach($app->categories->take(3) as $category)
                    <span
                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        {{ $category->name }}
                    </span>
                    @endforeach
                </div>

                <!-- Stats -->
                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        {{ $app->views_count }} views
                    </span>
                    <span class="flex items-center">
                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                        {{ $app->favorites()->count() }} favorit
                    </span>
                </div>

                <!-- Action Button -->
                <a href="{{ $app->url }}" target="_blank" onclick="trackAppClick({{ $app->id }})"
                    class="block w-full text-center px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition">
                    Akses Layanan
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada layanan</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('search'))
                    Tidak ada layanan yang sesuai dengan pencarian "{{ request('search') }}"
                    @else
                    Belum ada layanan yang tersedia di kategori ini.
                    @endif
                </p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
    <div class="mt-6">
        {{ $applications->links() }}
    </div>
    @endif
</div>

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