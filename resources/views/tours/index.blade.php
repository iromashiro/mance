@extends('layouts.app')

@section('title', 'Wisata')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">
    <!-- Hero -->
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-primary-600 via-primary-700 to-accent-600 text-white shadow-2xl mb-8">
        <div class="absolute inset-0 opacity-20">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white rounded-full blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 w-60 h-60 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="relative p-8 lg:p-12">
            <h1 class="text-2xl lg:text-3xl font-heading font-extrabold">Jelajahi Destinasi Wisata</h1>
            <p class="mt-2 text-white/90">Temukan tempat menarik di Kabupaten Muara Enim</p>
        </div>
    </div>

    @if($tours->count() > 0)
    <!-- Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($tours as $tour)
        <article class="group relative overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg transition">
            <!-- Image -->
            <div class="relative h-44 overflow-hidden">
                @if(!empty($tour->image_url))
                <img src="{{ \Illuminate\Support\Str::startsWith($tour->image_url, ['http://','https://']) ? $tour->image_url : Storage::url($tour->image_url) }}"
                    alt="{{ $tour->title }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">Tidak ada gambar
                </div>
                @endif
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-60 group-hover:opacity-70 transition">
                </div>
            </div>

            <!-- Content -->
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                    <a href="{{ route('tours.show', $tour->id) }}" class="hover:text-primary-600">{{ $tour->title }}</a>
                </h3>
                @if(!empty($tour->address))
                <p class="text-sm text-gray-600 line-clamp-2">{{ $tour->address }}</p>
                @else
                <p class="text-sm text-gray-600 line-clamp-2">{{ $tour->excerpt }}</p>
                @endif

                <div class="mt-4 flex items-center justify-between">
                    <span
                        class="text-xs text-gray-500">{{ $tour->published_at ? $tour->published_at->format('d M Y') : '-' }}</span>
                    <div class="flex gap-2">
                        @if(!empty($tour->gmap))
                        <a href="{{ $tour->gmap }}" target="_blank"
                            class="inline-flex items-center px-3 py-1.5 text-xs rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                            Maps
                        </a>
                        @endif
                        <a href="{{ route('tours.show', $tour->id) }}"
                            class="inline-flex items-center px-3 py-1.5 text-xs rounded-lg bg-primary-600 text-white hover:bg-primary-700">
                            Lihat
                        </a>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($tours->hasPages())
    <div class="mt-10">
        {{ $tours->links() }}
    </div>
    @endif

    @else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow p-12 text-center">
        <div
            class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-4">
            <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada data wisata</h3>
        <p class="text-gray-500">Data tidak tersedia saat ini.</p>
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
</style>
@endpush
@endsection