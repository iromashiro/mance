@extends('layouts.app')

@section('title', 'Berita')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Berita & Informasi
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Berita terkini seputar Kabupaten Muara Enim
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4">
                    <span class="text-sm text-gray-500">
                        {{ $news->total() }} artikel
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        {{-- Featured News (if exists) --}}
        @if(isset($featuredNews) && $featuredNews)
        <div class="mb-8">
            <article
                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                <a href="{{ route('news.show', $featuredNews->id ?? $featuredNews) }}" class="group block">
                    <div class="relative">
                        @php
                        $imageUrl = $featuredNews->image_url ?? null;
                        @endphp
                        @if($imageUrl)
                        <img src="{{ \Illuminate\Support\Str::startsWith($imageUrl, ['http://','https://']) ? $imageUrl : Storage::url($imageUrl) }}"
                            alt="{{ $featuredNews->title }}" class="w-full h-64 sm:h-80 object-cover">
                        @else
                        <div
                            class="w-full h-64 sm:h-80 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-accent-500 text-white text-xs font-medium rounded-lg">
                                Berita Utama
                            </span>
                        </div>
                    </div>
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-4 text-xs text-gray-500 mb-3">
                            @php
                            $publishedAt = $featuredNews->published_at ?? null;
                            if (is_string($publishedAt)) {
                            $publishedAt = \Carbon\Carbon::parse($publishedAt);
                            }
                            @endphp
                            <span>{{ $publishedAt ? $publishedAt->format('d M Y') : 'Hari ini' }}</span>
                            <span>•</span>
                            <span>{{ $featuredNews->category ?? 'Umum' }}</span>
                            <span>•</span>
                            @php
                            $viewCount = 0;
                            if (method_exists($featuredNews, 'views')) {
                            $viewCount = $featuredNews->views()->count();
                            } else {
                            $viewCount = $featuredNews->views ?? 0;
                            }
                            @endphp
                            <span>{{ number_format($viewCount) }} views</span>
                        </div>
                        <h2
                            class="text-2xl font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-3">
                            {{ $featuredNews->title }}
                        </h2>
                        <p class="text-gray-600 line-clamp-3">
                            {{ $featuredNews->excerpt ?? '' }}
                        </p>
                        <div
                            class="mt-4 inline-flex items-center text-primary-600 font-medium text-sm group-hover:text-primary-700">
                            Baca selengkapnya
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            </article>
        </div>
        @endif

        {{-- Search & Filter --}}
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row gap-4">
                {{-- Search Bar --}}
                <div class="flex-1 max-w-lg">
                    <form method="GET" action="{{ route('news.index') }}" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berita..."
                            class="w-full px-4 py-2 pl-10 pr-4 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>
                </div>

                {{-- Category Filter --}}
                <select name="category"
                    onchange="window.location.href='{{ route('news.index') }}?category=' + this.value + '{{ request('search') ? '&search=' . request('search') : '' }}'"
                    class="px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    <option value="pengumuman" {{ request('category') == 'pengumuman' ? 'selected' : '' }}>Pengumuman
                    </option>
                    <option value="berita" {{ request('category') == 'berita' ? 'selected' : '' }}>Berita</option>
                    <option value="event" {{ request('category') == 'event' ? 'selected' : '' }}>Event</option>
                    <option value="informasi" {{ request('category') == 'informasi' ? 'selected' : '' }}>Informasi
                    </option>
                </select>
            </div>
        </div>

        {{-- News Grid --}}
        @if($news->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($news as $item)
            <article
                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all group">
                <a href="{{ route('news.show', $item->id ?? $item) }}" class="block">
                    {{-- Image --}}
                    <div class="aspect-video bg-gray-100 relative overflow-hidden">
                        @php
                        $imageUrl = $item->image_url ?? null;
                        @endphp
                        @if($imageUrl)
                        <img src="{{ \Illuminate\Support\Str::startsWith($imageUrl, ['http://','https://']) ? $imageUrl : Storage::url($imageUrl) }}"
                            alt="{{ $item->title }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15" />
                            </svg>
                        </div>
                        @endif

                        @if($item->category ?? null)
                        <div class="absolute top-2 left-2">
                            <span
                                class="px-2.5 py-1 bg-white/90 backdrop-blur text-xs font-medium text-gray-700 rounded-lg">
                                {{ $item->category }}
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        {{-- Meta --}}
                        <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                            @php
                            $publishedAt = $item->published_at ?? null;
                            if (is_string($publishedAt)) {
                            $publishedAt = \Carbon\Carbon::parse($publishedAt);
                            }
                            @endphp
                            <span>{{ $publishedAt ? $publishedAt->format('d M Y') : 'Hari ini' }}</span>
                            <span>•</span>
                            <span class="flex items-center">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                @php
                                $viewCount = 0;
                                if (method_exists($item, 'views')) {
                                $viewCount = $item->views()->count();
                                } else {
                                $viewCount = $item->views ?? 0;
                                }
                                @endphp
                                {{ number_format($viewCount) }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h3
                            class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors line-clamp-2">
                            {{ $item->title }}
                        </h3>

                        {{-- Excerpt --}}
                        <p class="text-sm text-gray-600 line-clamp-3">
                            {{ $item->excerpt ?? '' }}
                        </p>

                        {{-- Read More --}}
                        <div
                            class="mt-4 inline-flex items-center text-primary-600 font-medium text-sm group-hover:text-primary-700">
                            Baca selengkapnya
                            <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </a>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($news->hasPages())
        <div class="mt-12">
            {{ $news->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="text-center py-16 px-4 bg-white rounded-xl border border-gray-200">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak ada berita</h3>
            <p class="text-sm text-gray-500 mb-6">
                @if(request('search'))
                Tidak ada berita yang sesuai dengan pencarian "{{ request('search') }}"
                @else
                Belum ada berita yang dipublikasikan
                @endif
            </p>
            @if(request()->hasAny(['search', 'category']))
            <a href="{{ route('news.index') }}"
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