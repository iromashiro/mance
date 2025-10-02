@extends('layouts.app')

@section('title', 'Berita')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Berita & Informasi</h1>
        <p class="text-gray-600 mt-2">Berita terkini seputar Kabupaten Muara Enim</p>
    </div>

    <!-- Featured News (Latest) -->
    @php
    $featuredNews = $news->first();
    $otherNews = $news->skip(1);
    @endphp

    @if($featuredNews)
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="lg:flex">
                @if($featuredNews->image_url)
                <div class="lg:w-1/2">
                    <img src="{{ Storage::url($featuredNews->image_url) }}" alt="{{ $featuredNews->title }}"
                        class="h-64 lg:h-full w-full object-cover">
                </div>
                @endif
                <div class="p-6 lg:w-1/2">
                    <div class="flex items-center mb-2">
                        <span class="bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded">
                            Berita Utama
                        </span>
                        <span class="text-gray-500 text-sm ml-3">
                            {{ $featuredNews->published_at->format('d M Y') }}
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-3">
                        <a href="{{ route('news.show', $featuredNews) }}" class="hover:text-primary-600">
                            {{ $featuredNews->title }}
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-4">{{ $featuredNews->excerpt }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ $featuredNews->author }}
                            <span class="mx-2">•</span>
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            {{ $featuredNews->views()->count() }} views
                        </div>
                        <a href="{{ route('news.show', $featuredNews) }}"
                            class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            Baca Selengkapnya
                            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($otherNews ?? $news as $item)
        <article class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200">
            @if($item->image_url)
            <a href="{{ route('news.show', $item) }}">
                <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->title }}"
                    class="h-48 w-full object-cover rounded-t-lg">
            </a>
            @else
            <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 rounded-t-lg flex items-center justify-center">
                <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
            </div>
            @endif

            <div class="p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500">
                        {{ $item->published_at->format('d M Y') }}
                    </span>
                    <div class="flex items-center text-xs text-gray-500">
                        <svg class="h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        {{ $item->views()->count() }}
                    </div>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 mb-2">
                    <a href="{{ route('news.show', $item) }}" class="hover:text-primary-600">
                        {{ Str::limit($item->title, 60) }}
                    </a>
                </h3>

                <p class="text-sm text-gray-600 mb-3">
                    {{ Str::limit($item->excerpt, 100) }}
                </p>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">
                        <svg class="inline h-3.5 w-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $item->author }}
                    </span>
                    <a href="{{ route('news.show', $item) }}"
                        class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                        Baca →
                    </a>
                </div>
            </div>
        </article>
        @empty
        <div class="col-span-full">
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada berita</h3>
                <p class="mt-1 text-sm text-gray-500">Berita akan segera tersedia.</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($news->hasPages())
    <div class="mt-8">
        {{ $news->links() }}
    </div>
    @endif
</div>
@endsection