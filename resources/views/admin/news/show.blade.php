@extends('layouts.admin')

@section('title', 'Detail Berita')

@section('header', 'Detail Berita')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.news.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            ← Kembali ke Daftar Berita
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.news.edit', $news) }}"
                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                Edit Berita
            </a>
            @if($news->is_published)
            <form action="{{ route('admin.news.unpublish', $news) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                    Unpublish
                </button>
            </form>
            @else
            <form action="{{ route('admin.news.publish', $news) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Publikasikan
                </button>
            </form>
            @endif
            <a href="{{ route('news.show', $news) }}" target="_blank"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Lihat di Frontend →
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $news->title }}</h1>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $news->author }}
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        {{ $news->created_at->format('d M Y, H:i') }}
                    </span>
                    @if($news->published_at)
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Terbit: {{ $news->published_at->format('d M Y') }}
                    </span>
                    @endif
                </div>
            </div>
            <div>
                @if($news->is_published)
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    ✓ Terbit
                </span>
                @else
                <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold">
                    Draft
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-3 gap-4 mb-6">
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
                    <p class="text-2xl font-bold text-gray-900">{{ $news->views()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Unique Readers</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $news->views()->distinct('user_id')->count('user_id') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="text-lg font-bold text-gray-900">{{ $news->is_published ? 'Published' : 'Draft' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Featured Image -->
        @if($news->image_url)
        <div class="w-full h-96 bg-gray-100">
            <img src="{{ Storage::url($news->image_url) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
        </div>
        @endif

        <!-- Content -->
        <div class="p-8">
            <!-- Excerpt -->
            <div class="mb-6 pb-6 border-b">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Ringkasan</h3>
                <p class="text-lg text-gray-700 leading-relaxed">{{ $news->excerpt }}</p>
            </div>

            <!-- Full Content -->
            <div class="prose prose-lg max-w-none">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Konten Lengkap</h3>
                <div class="text-gray-800 leading-relaxed whitespace-pre-wrap">{{ $news->content }}</div>
            </div>

            <!-- Metadata -->
            <div class="mt-8 pt-6 border-t">
                <h3 class="text-sm font-semibold text-gray-500 uppercase mb-4">Metadata</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Slug</dt>
                        <dd class="font-medium text-gray-900 font-mono">{{ $news->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Penulis</dt>
                        <dd class="font-medium text-gray-900">{{ $news->author }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Dibuat</dt>
                        <dd class="font-medium text-gray-900">{{ $news->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Terakhir Diubah</dt>
                        <dd class="font-medium text-gray-900">{{ $news->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($news->published_at)
                    <div>
                        <dt class="text-gray-500">Tanggal Terbit</dt>
                        <dd class="font-medium text-gray-900">{{ $news->published_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Recent Viewers -->
    @if($news->views->count() > 0)
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h3 class="text-lg font-semibold mb-4">Pembaca Terbaru</h3>
        <div class="space-y-3">
            @foreach($news->views->take(10) as $view)
            <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-primary-600 text-sm font-semibold">
                            {{ substr($view->user->name, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $view->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $view->user->email }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $view->created_at->diffForHumans() }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
