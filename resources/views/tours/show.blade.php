@extends('layouts.app')

@section('title', $tour->title)

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <!-- Back -->
    <div class="mb-6">
        <a href="{{ route('tours.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Wisata
        </a>
    </div>

    <!-- Header Card -->
    <div class="overflow-hidden rounded-3xl bg-white shadow-lg mb-8">
        @if(!empty($tour->image_url))
        <div class="relative">
            <img src="{{ \Illuminate\Support\Str::startsWith($tour->image_url, ['http://','https://']) ? $tour->image_url : Storage::url($tour->image_url) }}"
                alt="{{ $tour->title }}" class="w-full h-72 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <h1 class="text-3xl font-heading font-extrabold text-white">{{ $tour->title }}</h1>
                @if(!empty($tour->address))
                <p class="text-white/90 mt-1">{{ $tour->address }}</p>
                @endif
            </div>
        </div>
        @else
        <div class="p-6">
            <h1 class="text-3xl font-heading font-extrabold text-gray-900">{{ $tour->title }}</h1>
            @if(!empty($tour->address))
            <p class="text-gray-600 mt-1">{{ $tour->address }}</p>
            @endif
        </div>
        @endif

        <div class="p-6">
            @if(!empty($tour->description))
            <div class="prose max-w-none text-gray-700">
                {!! nl2br(e($tour->description)) !!}
            </div>
            @endif

            <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-gray-500">
                <span>
                    Dipublikasikan:
                    <strong
                        class="text-gray-700">{{ $tour->published_at ? $tour->published_at->translatedFormat('d M Y') : '-' }}</strong>
                </span>
                @if(!empty($tour->gmap))
                <a href="{{ $tour->gmap }}" target="_blank"
                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Buka di Google Maps
                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- 3D Map -->
    @if(!empty($mapsKey) && !empty($tour->lat) && !empty($tour->lng))
    <div class="mb-8">
        <div class="rounded-3xl overflow-hidden shadow-lg border border-gray-100">
            <gmp-map-3d mode="hybrid" center="{{ $tour->lat }}, {{ $tour->lng }}" range="200" tilt="65" heading="10"
                style="display:block; width:100%; height:480px;">
            </gmp-map-3d>
        </div>
        <script async defer
            src="https://maps.googleapis.com/maps/api/js?key={{ $mapsKey }}&v=beta&libraries=maps3d&loading=async">
        </script>
        <p class="text-xs text-gray-500 mt-2">Tampilan peta 3D photorealistic (beta) dari Google Maps.</p>
    </div>
    @endif

    <!-- Related -->
    @if($related->count() > 0)
    <div class="mt-10">
        <h2 class="text-xl font-heading font-bold text-gray-900 mb-4">Destinasi Lainnya</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($related as $item)
            <a href="{{ route('tours.show', $item->id) }}"
                class="group overflow-hidden rounded-2xl bg-white shadow hover:shadow-lg transition">
                <div class="h-40 overflow-hidden">
                    @if(!empty($item->image_url))
                    <img src="{{ \Illuminate\Support\Str::startsWith($item->image_url, ['http://','https://']) ? $item->image_url : Storage::url($item->image_url) }}"
                        alt="{{ $item->title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                    <div class="w-full h-full bg-gray-100 flex items-center justify-center text-gray-400">Tidak ada
                        gambar</div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="text-base font-semibold text-gray-900 line-clamp-2">{{ $item->title }}</h3>
                    @if(!empty($item->address))
                    <p class="text-sm text-gray-600 line-clamp-2 mt-1">{{ $item->address }}</p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
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
</style>
@endpush
@endsection