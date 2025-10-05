@extends('layouts.app')

@section('title', $application->name)

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-5xl mx-auto">
    <!-- Header / Banner -->
    <div
        class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 via-accent-600 to-pink-600 text-white shadow-2xl mb-8">
        <div class="absolute inset-0">
            <div class="absolute -top-6 -left-6 w-40 h-40 bg-white/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-56 h-56 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="relative p-8 lg:p-10">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-4">
                    @if($application->icon_url)
                    <img src="{{ Storage::url($application->icon_url) }}" alt="{{ $application->name }}"
                        class="h-16 w-16 rounded-2xl ring-2 ring-white/30">
                    @else
                    <div
                        class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center ring-2 ring-white/30">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    @endif
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-heading font-bold">{{ $application->name }}</h1>
                        @if($application->categories->first())
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 mt-2 rounded-full text-xs font-semibold bg-white/20 ring-1 ring-white/30">
                            {{ $application->categories->first()->name }}
                        </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col items-end space-y-2">
                    @if($application->url)
                    <a href="{{ $application->url }}" target="_blank" onclick="trackAppClick({{ $application->id }})"
                        class="inline-flex items-center px-4 py-2 bg-white text-primary-700 font-semibold rounded-xl hover:shadow-lg transform hover:scale-[1.02] transition">
                        Buka Layanan
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                    @endif
                    <form action="{{ route('applications.favorite', $application) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-3 py-1.5 bg-white/10 border border-white/30 rounded-xl text-sm hover:bg-white/20 transition">
                            @if(Auth::check() && Auth::user()->favorites()->where('application_id',
                            $application->id)->exists())
                            ★ Hapus Favorit
                            @else
                            ☆ Tambah Favorit
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi</h2>
                <p class="text-gray-700 leading-relaxed">
                    {{ $application->description ?: 'Belum ada deskripsi untuk layanan ini.' }}
                </p>

                @if($application->url)
                <div class="mt-6">
                    <a href="{{ $application->url }}" target="_blank" onclick="trackAppClick({{ $application->id }})"
                        class="btn btn-primary inline-flex items-center">
                        Buka Layanan
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>

            @if($relatedApps->count() > 0)
            <div class="mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Layanan Terkait</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($relatedApps as $app)
                    <div class="bg-white rounded-2xl shadow p-4 hover:shadow-md transition">
                        <div class="flex items-start space-x-3">
                            @if($app->icon_url)
                            <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}"
                                class="h-12 w-12 rounded-xl">
                            @else
                            <div class="h-12 w-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                <svg class="h-7 w-7 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">{{ $app->name }}</h4>
                                <p class="text-xs text-gray-500 line-clamp-2">{{ $app->description }}</p>
                                <div class="mt-3 flex items-center space-x-2">
                                    <a href="{{ $app->url }}" target="_blank" onclick="trackAppClick({{ $app->id }})"
                                        class="text-sm text-primary-600 hover:text-primary-700 font-medium">Buka →</a>
                                    <a href="{{ route('applications.show', $app) }}"
                                        class="text-sm text-gray-600 hover:text-gray-800">Detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistik</h3>
                <dl class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Dilihat</dt>
                        <dd class="font-semibold text-gray-900">{{ number_format($application->views_count ?? 0) }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Favorit</dt>
                        <dd class="font-semibold text-gray-900">{{ number_format($application->favorites()->count()) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd class="font-semibold text-gray-900">{{ $application->is_active ? 'Aktif' : 'Nonaktif' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Dibuat</dt>
                        <dd class="font-semibold text-gray-900">{{ $application->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>

            @if($application->categories->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Kategori</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($application->categories as $cat)
                    <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                        {{ $cat->name }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    function trackAppClick(appId) {
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
