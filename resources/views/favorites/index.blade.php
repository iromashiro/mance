@extends('layouts.app')

@section('title', 'Favorit Saya')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-6 flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-heading font-bold text-gray-900">Favorit Saya</h1>
            <p class="text-sm text-gray-600 mt-1">Daftar layanan yang Anda simpan sebagai favorit</p>
        </div>
        <div class="text-sm text-gray-500">
            Total: {{ number_format($favorites->total()) }} layanan
        </div>
    </div>

    @if($favorites->count() > 0)
    <!-- Favorites Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($favorites as $fav)
        @php $app = $fav->application; @endphp
        @if(!$app) @continue @endif

        <div class="group relative">
            <!-- Glow -->
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-primary-400 to-accent-400 rounded-2xl blur opacity-0 group-hover:opacity-25 transition">
            </div>

            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                <!-- Category -->
                @if($app->categories->first())
                <div class="absolute top-3 left-3 z-10">
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/90 backdrop-blur-sm text-gray-700 shadow-md">
                        {{ $app->categories->first()->name }}
                    </span>
                </div>
                @endif

                <!-- Unfavorite -->
                <div class="absolute top-3 right-3 z-10">
                    <form action="{{ route('applications.favorite', $app) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="p-2 rounded-lg bg-white/90 backdrop-blur-sm shadow-md hover:shadow-lg transition group/fav"
                            title="Hapus dari Favorit">
                            <svg class="h-5 w-5 text-red-500 group-hover/fav:scale-110 transition-transform"
                                fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Header -->
                <div
                    class="relative h-32 bg-gradient-to-br from-primary-100 via-accent-50 to-pink-100 p-6 flex items-center justify-center">
                    @if($app->icon_url)
                    <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}"
                        class="h-16 w-16 object-contain filter drop-shadow">
                    @elseif($app->icon_path)
                    <img src="{{ Storage::url($app->icon_path) }}" alt="{{ $app->name }}"
                        class="h-16 w-16 object-contain filter drop-shadow">
                    @else
                    <div class="h-16 w-16 bg-white rounded-2xl shadow flex items-center justify-center">
                        <svg class="h-10 w-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Body -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1 group-hover:text-primary-600 transition">
                        {{ $app->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $app->description }}</p>

                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{ number_format($app->views_count ?? 0) }}
                        </span>
                        <span class="flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            {{ number_format($app->favorites()->count()) }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($app->url)
                        <a href="{{ $app->url }}" target="_blank" onclick="trackAppClick({{ $app->id }})"
                            class="btn btn-primary flex-1 justify-center">
                            Buka Layanan
                        </a>
                        @endif
                        <a href="{{ route('applications.show', $app) }}"
                            class="btn btn-secondary flex-1 justify-center">
                            Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($favorites->hasPages())
    <div class="mt-12">
        {{ $favorites->links() }}
    </div>
    @endif

    @else
    <!-- Empty state -->
    <div class="bg-white rounded-2xl shadow p-12 text-center">
        <div
            class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-4">
            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada favorit</h3>
        <p class="text-gray-500">Tambahkan layanan ke favorit dari halaman Layanan.</p>
        <a href="{{ route('applications.index') }}" class="btn btn-primary mt-4">Jelajahi Layanan</a>
    </div>
    @endif
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
    function trackAppClick(appId) {
        fetch(`/api/applications/${appId}/track`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
        });
    }
</script>
@endpush
@endsection
