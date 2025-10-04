<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/js/app.js')
    <title>Layanan - Embed</title>
    <style>
        [x-cloak] {
            display: none !important
        }

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
</head>

<body class="bg-white">
    <div class="px-4 py-4 sm:px-6 lg:px-8">

        {{-- Search + Filters (simple for embed) --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-900">Layanan Publik</h2>
                <div class="text-sm text-gray-500">
                    {{ $applications->total() }} layanan
                </div>
            </div>

            <div class="grid gap-3 sm:grid-cols-3">
                <div class="sm:col-span-2">
                    <form action="{{ route('applications.index') }}" method="GET" class="relative">
                        <input type="hidden" name="embed" value="1">
                        @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari layanan..."
                            class="w-full px-4 py-2.5 pr-10 rounded-xl bg-white text-gray-900 placeholder-gray-500 border border-gray-200 focus:ring-2 focus:ring-primary-200 focus:border-primary-300 shadow-sm">
                        <button type="submit"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-primary-600 hover:text-primary-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="sm:col-span-1">
                    <!-- Mobile Dropdown -->
                    <select class="form-select w-full"
                        onchange="window.location.href = '{{ route('applications.index') }}?embed=1&category=' + this.value">
                        <option value="" {{ !request('category') ? 'selected' : '' }}>🎯 Semua</option>
                        @foreach(\App\Models\Category::withCount('applications')->get() as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }} ({{ $cat->applications_count }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Desktop Pills -->
            <div class="hidden sm:flex flex-wrap gap-2 mt-3">
                <a href="{{ route('applications.index', ['embed' => 1]) }}"
                    class="@if(!request('category')) bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow @else bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 @endif px-3 py-1.5 rounded-full text-sm transition">
                    🎯 Semua
                </a>
                @foreach(\App\Models\Category::withCount('applications')->get() as $cat)
                <a href="{{ route('applications.index', ['category' => $cat->slug, 'embed' => 1]) }}"
                    class="@if(request('category') == $cat->slug) bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow @else bg-white text-gray-700 hover:bg-gray-50 border border-gray-200 @endif px-3 py-1.5 rounded-full text-sm transition">
                    {{ $cat->name }} <span class="text-xs opacity-70">({{ $cat->applications_count }})</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Applications Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
            @forelse($applications as $app)
            <div class="group relative">
                <div
                    class="relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
                    <!-- Category Badge -->
                    @if($app->categories->first())
                    <div class="absolute top-3 left-3 z-10">
                        <span
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/90 text-gray-700 shadow-sm border border-gray-200">
                            {{ $app->categories->first()->name }}
                        </span>
                    </div>
                    @endif

                    <!-- Favorite Button -->
                    <div class="absolute top-3 right-3 z-10">
                        <form action="{{ route('applications.favorite', $app) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="p-2 rounded-lg bg-white/95 shadow border border-gray-200 hover:shadow-md transition group/fav">
                                @if(Auth::check() && Auth::user()->favorites()->where('application_id',
                                $app->id)->exists())
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                                </svg>
                                @else
                                <svg class="h-5 w-5 text-gray-400 group-hover/fav:text-red-400 transition-colors"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                                @endif
                            </button>
                        </form>
                    </div>

                    <!-- Header/Icon -->
                    <div class="relative h-28 bg-gradient-to-br from-primary-50 via-accent-50 to-pink-50 p-6">
                        <div class="flex items-center justify-center h-full">
                            @if($app->icon_url)
                            <img src="{{ Storage::url($app->icon_url) }}" alt="{{ $app->name }}"
                                class="h-14 w-14 object-contain">
                            @else
                            <div class="h-14 w-14 bg-white rounded-xl shadow flex items-center justify-center">
                                <svg class="h-8 w-8 text-primary-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                    </path>
                                </svg>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-5">
                        <h3 class="text-base font-semibold text-gray-900 mb-1 group-hover:text-primary-600 transition">
                            {{ $app->name }}
                        </h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $app->description }}</p>

                        <!-- Stats -->
                        <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                {{ number_format($app->views_count) }}
                            </span>
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg>
                                {{ number_format($app->favorites()->count()) }}
                            </span>
                            <span class="flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                {{ number_format($app->users_count ?? rand(100, 1000)) }}
                            </span>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ $app->url }}" target="_blank" onclick="trackAppClick({{ $app->id }})"
                            class="btn btn-primary w-full justify-center group/btn">
                            <span>Akses Layanan</span>
                            <svg class="ml-2 h-4 w-4 group-hover/btn:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 mb-4">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900 mb-2">Tidak ada layanan ditemukan</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        @if(request('search'))
                        Tidak ada layanan untuk kata kunci "<span class="font-medium">{{ request('search') }}</span>".
                        @else
                        Belum ada layanan pada kategori ini.
                        @endif
                    </p>
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($applications->hasPages())
        <div class="mt-8">
            {{ $applications->appends([
                'embed' => 1,
                'category' => request('category'),
                'search' => request('search')
            ])->links() }}
        </div>
        @endif
    </div>

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
</body>

</html>
