@extends('layouts.admin')

@section('title', 'Detail Aplikasi')

@section('header', 'Detail Aplikasi')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.applications.index') }}"
            class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            ← Kembali ke Daftar Aplikasi
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.applications.edit', $application) }}"
                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                Edit Aplikasi
            </a>
            <a href="{{ $application->url }}" target="_blank"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Buka Aplikasi →
            </a>
        </div>
    </div>

    <!-- Application Info -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-start gap-6">
            @if($application->icon_path)
            <img src="{{ Storage::url($application->icon_path) }}" alt="{{ $application->name }}"
                class="w-24 h-24 rounded-lg shadow-md object-cover flex-shrink-0">
            @else
            <div
                class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center flex-shrink-0">
                <span class="text-white text-2xl font-bold">
                    {{ strtoupper(substr($application->name, 0, 2)) }}
                </span>
            </div>
            @endif

            <div class="flex-1">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $application->name }}</h1>
                        <p class="text-gray-600 mb-4">{{ $application->description }}</p>
                        <a href="{{ $application->url }}" target="_blank"
                            class="text-primary-600 hover:text-primary-800 text-sm font-medium inline-flex items-center">
                            {{ $application->url }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                </path>
                            </svg>
                        </a>
                    </div>
                    <div>
                        @if($application->is_active)
                        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            ✓ Aktif
                        </span>
                        @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                            Nonaktif
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Favorit</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_favorites'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Views</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['views_count'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Klik</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['clicks_count'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Kategori</h3>
        @if($application->categories->count() > 0)
        <div class="flex flex-wrap gap-2">
            @foreach($application->categories as $category)
            <span class="px-4 py-2 bg-primary-100 text-primary-800 rounded-full text-sm font-medium">
                {{ $category->name }}
            </span>
            @endforeach
        </div>
        @else
        <p class="text-gray-500">Belum ada kategori</p>
        @endif
    </div>

    <!-- Recent Favorites -->
    @if($application->userFavorites->count() > 0)
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Pengguna yang Memfavoritkan ({{ $application->userFavorites->count() }})
        </h3>
        <div class="space-y-3">
            @foreach($application->userFavorites->take(10) as $favorite)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-primary-600 text-sm font-semibold">
                            {{ substr($favorite->user->name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $favorite->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $favorite->user->email }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $favorite->created_at->diffForHumans() }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Metadata -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Informasi Tambahan</h3>
        <dl class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd class="font-medium text-gray-900">{{ $application->is_active ? 'Aktif' : 'Nonaktif' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Total Kategori</dt>
                <dd class="font-medium text-gray-900">{{ $application->categories->count() }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Dibuat</dt>
                <dd class="font-medium text-gray-900">{{ $application->created_at->format('d M Y, H:i') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Terakhir Diubah</dt>
                <dd class="font-medium text-gray-900">{{ $application->updated_at->format('d M Y, H:i') }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
