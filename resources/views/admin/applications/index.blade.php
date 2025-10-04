@extends('layouts.admin')

@section('title', 'Kelola Aplikasi')

@section('header', 'Kelola Aplikasi')

@section('content')
<!-- Action Bar -->
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Manajemen Aplikasi</h2>
        <p class="text-sm text-gray-600 mt-1">Kelola layanan dan aplikasi yang tersedia untuk pengguna</p>
    </div>
    <a href="{{ route('admin.applications.create') }}"
        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Aplikasi
    </a>
</div>

<!-- Filter Section -->
<div class="bg-white shadow rounded-lg mb-6">
    <div class="p-4">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                    placeholder="Nama aplikasi..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div class="w-40">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    Filter
                </button>
                <a href="{{ route('admin.applications.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                    </path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Aplikasi</p>
                <p class="text-xl font-semibold">{{ \App\Models\Application::count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex items-center">
            <div class="bg-green-100 rounded-full p-3">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Aktif</p>
                <p class="text-xl font-semibold">{{ \App\Models\Application::where('is_active', true)->count() }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-lg shadow">
        <div class="flex items-center">
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm text-gray-500">Total Favorit</p>
                <p class="text-xl font-semibold">{{ \App\Models\UserFavorite::count() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Applications Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($applications as $application)
    <div class="bg-white rounded-lg shadow hover:shadow-lg transition-all overflow-hidden">
        <!-- Header with Status Badge -->
        <div class="relative">
            @if($application->icon_path)
            <div class="h-32 bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                <img src="{{ asset('storage/' . $application->icon_path) }}" alt="{{ $application->name }}"
                    class="h-16 w-16 object-contain">
            </div>
            @else
            <div class="h-32 bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center">
                <svg class="h-16 w-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                    </path>
                </svg>
            </div>
            @endif
            <div class="absolute top-3 right-3">
                @if($application->is_active)
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    Aktif
                </span>
                @else
                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                    Nonaktif
                </span>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $application->name }}</h3>
            <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $application->description }}</p>

            <!-- Stats -->
            <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200">
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                    {{ $application->user_favorites_count ?? 0 }} favorit
                </div>
                <div class="flex flex-wrap gap-1">
                    @foreach($application->categories->take(2) as $category)
                    <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-800">
                        {{ $category->name }}
                    </span>
                    @endforeach
                    @if($application->categories->count() > 2)
                    <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 text-gray-600">
                        +{{ $application->categories->count() - 2 }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('admin.applications.show', $application) }}"
                    class="text-sm font-medium text-primary-600 hover:text-primary-800">
                    Detail
                </a>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('admin.applications.edit', $application) }}"
                        class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('admin.applications.destroy', $application) }}"
                        onsubmit="return confirm('Yakin ingin menghapus aplikasi ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-lg shadow p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                </path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada aplikasi</h3>
            <p class="text-gray-500 mb-6">Mulai tambahkan aplikasi untuk ditampilkan kepada pengguna</p>
            <a href="{{ route('admin.applications.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Aplikasi Pertama
            </a>
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
@endsection
