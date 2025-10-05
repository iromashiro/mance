@extends('layouts.app')

@section('title', 'Berita & Informasi')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Hero Section --}}
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-primary-700 to-accent-600 shadow-2xl mb-8">
        <!-- Animated Background -->
        <div class="absolute inset-0">
            <div class="absolute top-0 left-0 w-96 h-96 bg-accent-400/20 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-primary-400/20 rounded-full blur-3xl animate-float">
            </div>
        </div>

        <div class="relative p-8 lg:p-12">
            <div class="max-w-3xl">
                <div class="flex items-center space-x-2 mb-4">
                    <span class="h-2 w-2 bg-red-400 rounded-full animate-pulse"></span>
                    <span class="text-white/90 text-sm font-medium uppercase tracking-wider">Breaking News</span>
                </div>
                <h1 class="text-3xl lg:text-5xl font-heading font-bold text-white mb-4 animate-slide-up">
                    Berita & Informasi Terkini 📰
                </h1>
                <p class="text-lg text-white/90 max-w-2xl">
                    Dapatkan informasi terbaru seputar Kabupaten Muara Enim. Berita lokal, pengumuman penting,
                    dan kisah inspiratif dari masyarakat kita.
                </p>
            </div>
        </div>
    </div>

    {{-- Category Pills --}}
    <div class="mb-8">
        <div class="flex flex-wrap gap-2">
            @php
            $categories = ['Semua', 'Politik', 'Ekonomi', 'Sosial', 'Budaya', 'Teknologi', 'Olahraga', 'Kesehatan'];
            @endphp
            @foreach($categories as $category)
            <button
                class="px-4 py-2 rounded-full text-sm font-medium transition-all transform hover:scale-105
                         {{ $loop->first ? 'bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-50 shadow-md' }}">
                {{ $category }}
            </button>
            @endforeach
        </div>
    </div>

    {{-- Magazine Layout Grid --}}
    @php
    // Gunakan featured dari controller jika tersedia; fallback ke item pertama
    $featuredNews = isset($featuredNews) ? $featuredNews : $news->first();
    // Buang featured dari daftar sekunder dan lainnya agar tidak duplikat
    $secondaryNews = $news->filter(fn($n) => ($n->id ?? null) !== ($featuredNews->id ?? null))->take(2);
    $otherNews = $news->filter(fn($n) => ($n->id ?? null) !== ($featuredNews->id ?? null))->skip(2);
    @endphp

    @if($featuredNews)
    {{-- Featured Article - Large Card --}}
    <div class="mb-8">
        <article
            class="group relative overflow-hidden rounded-3xl bg-white shadow-xl hover:shadow-2xl transition-all duration-300">
            <!-- Hover Glow -->
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-primary-400 to-accent-400 rounded-3xl blur opacity-0 group-hover:opacity-30 transition-opacity duration-300">
            </div>

            <div class="relative lg:grid lg:grid-cols-2">
                <!-- Image Section -->
                <div class="relative h-96 lg:h-auto overflow-hidden">
                    @if($featuredNews->image_url)
                    <img src="{{ \Illuminate\Support\Str::startsWith($featuredNews->image_url, ['http://','https://']) ? $featuredNews->image_url : Storage::url($featuredNews->image_url) }}"
                        alt="{{ $featuredNews->title }}"
                        class="absolute inset-0 h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                        <svg class="h-32 w-32 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    @endif

                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>

                    <!-- Featured Badge -->
                    <div class="absolute top-6 left-6">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-500 text-white shadow-lg">
                            <svg class="mr-1 h-3 w-3 animate-pulse" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd"
                                    d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Berita Utama
                        </span>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-8 lg:p-12 flex flex-col justify-between">
                    <div>
                        <!-- Meta Info -->
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                            <span class="flex items-center">
                                <img class="h-6 w-6 rounded-full mr-2"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(data_get($featuredNews, 'author.name', 'Admin Muara Enim')) }}&background=7950f2&color=fff&size=32"
                                    alt="{{ data_get($featuredNews, 'author.name', 'Admin Muara Enim') }}">
                                {{ data_get($featuredNews, 'author.name', 'Admin Muara Enim') }}
                            </span>
                            <span>•</span>
                            <span>{{ $featuredNews->published_at->format('d M Y') }}</span>
                            <span>•</span>
                            <span>5 min read</span>
                        </div>

                        <!-- Title -->
                        <h2
                            class="text-3xl lg:text-4xl font-heading font-bold text-gray-900 mb-4 group-hover:text-primary-600 transition-colors">
                            <a href="{{ route('news.show', $featuredNews->id) }}">
                                {{ $featuredNews->title }}
                            </a>
                        </h2>

                        <!-- Excerpt -->
                        <p class="text-lg text-gray-600 mb-6 line-clamp-3">
                            {{ $featuredNews->excerpt }}
                        </p>
                    </div>

                    <!-- Footer -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-6 text-sm text-gray-500">
                            <span class="flex items-center">
                                <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format(method_exists($featuredNews, 'views') ? $featuredNews->views()->count() : ($featuredNews->views ?? 0)) }}
                            </span>
                            <span class="flex items-center">
                                <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                {{ rand(10, 100) }}
                            </span>
                        </div>
                        <a href="{{ route('news.show', $featuredNews->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-primary-600 to-accent-600 text-white rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all">
                            Baca
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    </div>
    @endif

    {{-- Secondary News Grid --}}
    @if($secondaryNews->count() > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        @foreach($secondaryNews as $item)
        <article
            class="group relative overflow-hidden rounded-2xl bg-white shadow-lg hover:shadow-xl transition-all duration-300">
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-primary-400 to-accent-400 rounded-2xl blur opacity-0 group-hover:opacity-20 transition-opacity">
            </div>
            <div class="relative">
                <!-- Image -->
                <div class="relative h-64 overflow-hidden">
                    @if($item->image_url)
                    <img src="{{ \Illuminate\Support\Str::startsWith($item->image_url, ['http://','https://']) ? $item->image_url : Storage::url($item->image_url) }}"
                        alt="{{ $item->title }}"
                        class="absolute inset-0 h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center">
                        <svg class="h-20 w-20 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>

                    <!-- Category Badge -->
                    <div class="absolute top-4 left-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold bg-white/90 backdrop-blur-sm text-gray-700 shadow-md">
                            {{ ['Politik', 'Ekonomi', 'Sosial', 'Budaya'][rand(0,3)] }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Meta -->
                    <div class="flex items-center space-x-3 text-xs text-gray-500 mb-3">
                        <span class="flex items-center">
                            <img class="h-5 w-5 rounded-full mr-1.5"
                                src="https://ui-avatars.com/api/?name={{ urlencode(data_get($item, 'author.name', 'Admin Muara Enim')) }}&background=7950f2&color=fff&size=32"
                                alt="{{ data_get($item, 'author.name', 'Admin Muara Enim') }}">
                            {{ data_get($item, 'author.name', 'Admin Muara Enim') }}
                        </span>
                        <span>•</span>
                        <span>{{ $item->published_at->format('d M Y') }}</span>
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                        <a href="{{ route('news.show', $item->id) }}">
                            {{ $item->title }}
                        </a>
                    </h3>

                    <!-- Excerpt -->
                    <p class="text-gray-600 mb-4 line-clamp-2">
                        {{ $item->excerpt }}
                    </p>

                    <!-- Footer -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                            <span class="flex items-center">
                                <svg class="mr-1 h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format(method_exists($item, 'views') ? $item->views()->count() : ($item->views ?? 0)) }}
                            </span>
                            <span>3 min read</span>
                        </div>
                        <a href="{{ route('news.show', $item->id) }}"
                            class="text-primary-600 hover:text-primary-700 font-medium flex items-center group/link">
                            <span>Baca</span>
                            <svg class="ml-1 h-4 w-4 group-hover/link:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
    </div>
    @endif

    {{-- Other News Grid with Sidebar --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Articles -->
        <div class="lg:col-span-2">
            <h2 class="text-xl font-heading font-bold text-gray-900 mb-6 flex items-center">
                <span class="h-1 w-12 bg-gradient-to-r from-primary-500 to-accent-500 rounded-full mr-3"></span>
                Berita Lainnya
            </h2>

            <div class="space-y-6">
                @forelse($otherNews ?? $news as $item)
                <article
                    class="group bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
                    <div class="sm:flex">
                        <!-- Image -->
                        <div class="sm:flex-shrink-0 sm:w-48 h-48 sm:h-auto relative overflow-hidden">
                            @if($item->image_url)
                            <img src="{{ \Illuminate\Support\Str::startsWith($item->image_url, ['http://','https://']) ? $item->image_url : Storage::url($item->image_url) }}"
                                alt="{{ $item->title }}"
                                class="absolute inset-0 h-full w-full object-cover group-hover:scale-105 transition-transform duration-500">
                            @else
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-1">
                            <div class="flex items-center space-x-3 text-xs text-gray-500 mb-2">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-md bg-gray-100 text-gray-700 font-medium">
                                    {{ ['Politik', 'Ekonomi', 'Sosial', 'Budaya', 'Teknologi'][rand(0,4)] }}
                                </span>
                                <span>{{ $item->published_at->format('d M Y') }}</span>
                            </div>

                            <h3
                                class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary-600 transition-colors">
                                <a href="{{ route('news.show', $item->id) }}">
                                    {{ $item->title }}
                                </a>
                            </h3>

                            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                {{ $item->excerpt }}
                            </p>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-xs text-gray-500">
                                    <img class="h-5 w-5 rounded-full mr-2"
                                        src="https://ui-avatars.com/api/?name={{ urlencode(data_get($item, 'author.name', 'Admin Muara Enim')) }}&background=7950f2&color=fff&size=32"
                                        alt="{{ data_get($item, 'author.name', 'Admin Muara Enim') }}">
                                    <span>{{ data_get($item, 'author.name', 'Admin Muara Enim') }}</span>
                                </div>
                                <a href="{{ route('news.show', $item->id) }}"
                                    class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                                    Baca →
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
                @empty
                <div class="text-center py-12">
                    <div
                        class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada berita lainnya</h3>
                    <p class="text-gray-500">Berita baru akan segera tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Trending News -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-heading font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="mr-2 h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z"
                            clip-rule="evenodd" />
                    </svg>
                    Trending Sekarang
                </h3>

                <div class="space-y-4">
                    @foreach(range(1, 5) as $index)
                    <div class="flex items-start space-x-3 group cursor-pointer">
                        <span
                            class="flex-shrink-0 h-8 w-8 bg-gradient-to-br from-primary-400 to-accent-400 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                            {{ $index }}
                        </span>
                        <div class="flex-1">
                            <h4
                                class="text-sm font-medium text-gray-900 group-hover:text-primary-600 transition-colors line-clamp-2">
                                Lorem ipsum dolor sit amet consectetur adipisicing elit
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">2 jam yang lalu</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Newsletter Subscription -->
            <div class="bg-gradient-to-br from-primary-600 to-accent-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-heading font-bold mb-2">
                    📧 Newsletter
                </h3>
                <p class="text-sm text-white/90 mb-4">
                    Dapatkan berita terbaru langsung di email Anda setiap hari.
                </p>
                <form class="space-y-3">
                    <input type="email" placeholder="Email Anda"
                        class="w-full px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm text-white placeholder-white/70 border border-white/30 focus:ring-2 focus:ring-white/50">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-white text-primary-700 rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all">
                        Subscribe
                    </button>
                </form>
            </div>

            <!-- Popular Tags -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-heading font-bold text-gray-900 mb-4">
                    🏷️ Topik Populer
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Pemilu', 'UMKM', 'Pendidikan', 'Infrastruktur', 'Wisata', 'COVID-19', 'Olahraga',
                    'Teknologi'] as $tag)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 hover:bg-primary-100 hover:text-primary-700 cursor-pointer transition-colors">
                        #{{ $tag }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    @if($news->hasPages())
    <div class="mt-12">
        {{ $news->links() }}
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
