@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-7xl">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            Halo, {{ auth()->user()->name }}!
        </h1>
        <p class="text-gray-600 mt-2">Selamat datang di Portal MANCE - Muara Enim</p>
    </div>

    <!-- Weather Widget -->
    <div class="bg-gradient-to-br from-primary-500 to-accent-500 rounded-2xl p-6 mb-8 text-white shadow-xl">
        <div x-data="weatherWidget()" x-init="init()">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Current Weather -->
                <div class="flex items-center space-x-4">
                    <div x-show="weather.icon" class="text-6xl" x-html="weather.icon"></div>
                    <div>
                        <div class="text-5xl font-bold" x-text="weather.temp + '°C'">--°C</div>
                        <div class="text-lg opacity-90" x-text="weather.condition">Loading...</div>
                    </div>
                </div>

                <!-- Weather Details -->
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="opacity-75">Terasa:</p>
                        <p class="font-semibold" x-text="weather.feels_like + '°C'">--°C</p>
                    </div>
                    <div>
                        <p class="opacity-75">Kelembapan:</p>
                        <p class="font-semibold" x-text="weather.humidity + '%'">--%</p>
                    </div>
                    <div>
                        <p class="opacity-75">UV Index:</p>
                        <p class="font-semibold" x-text="weather.uv">0</p>
                    </div>
                    <div>
                        <p class="opacity-75">Kualitas Udara:</p>
                        <div class="inline-flex items-center gap-1">
                            <span class="font-semibold" x-text="weather.air_quality">--</span>
                            <span class="text-xs px-2 py-0.5 bg-yellow-300 text-yellow-800 rounded-full"
                                x-text="weather.air_quality_text">--</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Create Report -->
            <a href="{{ route('complaints.create') }}"
                class="group bg-white rounded-xl p-4 border border-gray-200 hover:border-primary-300 hover:shadow-lg transition-all">
                <div class="flex flex-col items-center text-center space-y-3">
                    <div
                        class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary-200 transition-colors">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Buat Laporan</h3>
                        <p class="text-xs text-gray-500 mt-1">Laporkan masalah</p>
                    </div>
                </div>
            </a>

            <!-- Services -->
            <a href="{{ route('applications.index') }}"
                class="group bg-white rounded-xl p-4 border border-gray-200 hover:border-secondary-300 hover:shadow-lg transition-all">
                <div class="flex flex-col items-center text-center space-y-3">
                    <div
                        class="w-12 h-12 bg-secondary-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-secondary-200 transition-colors">
                        <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Layanan</h3>
                        <p class="text-xs text-gray-500 mt-1">Akses layanan</p>
                    </div>
                </div>
            </a>

            <!-- Tourist -->
            <a href="{{ route('tours.index') }}"
                class="group bg-white rounded-xl p-4 border border-gray-200 hover:border-accent-300 hover:shadow-lg transition-all">
                <div class="flex flex-col items-center text-center space-y-3">
                    <div
                        class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-accent-200 transition-colors">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Wisata</h3>
                        <p class="text-xs text-gray-500 mt-1">Destinasi wisata</p>
                    </div>
                </div>
            </a>

            <!-- Profile -->
            <a href="{{ route('profile.index') }}"
                class="group bg-white rounded-xl p-4 border border-gray-200 hover:border-gray-300 hover:shadow-lg transition-colors">
                <div class="flex flex-col items-center text-center space-y-3">
                    <div
                        class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-gray-200 transition-colors">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">Profil</h3>
                        <p class="text-xs text-gray-500 mt-1">Pengaturan akun</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent News -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Berita Terkini</h2>
            <a href="{{ route('news.index') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @if($recentNews->count() > 0)
            <div class="space-y-4">
                @foreach($recentNews as $news)
                <a href="{{ route('news.show', $news->id) }}"
                    class="group block rounded-xl hover:bg-gradient-to-r hover:from-gray-50 hover:to-primary-50 p-3 -m-3 transition-all">
                    <div class="flex space-x-4">
                        @if($news->image_url)
                        <img src="{{ \Illuminate\Support\Str::startsWith($news->image_url, ['http://','https://']) ? $news->image_url : Storage::url($news->image_url) }}"
                            alt="{{ $news->title }}"
                            class="h-20 w-20 object-cover rounded-xl shadow-md group-hover:shadow-lg transition-shadow">
                        @else
                        <div
                            class="h-20 w-20 bg-gradient-to-br from-primary-100 to-accent-100 rounded-xl flex items-center justify-center shadow-md group-hover:shadow-lg transition-shadow">
                            <svg class="h-8 w-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3
                                class="text-sm font-semibold text-gray-900 group-hover:text-primary-700 transition-colors line-clamp-2">
                                {{ $news->title }}</h3>
                            <p class="text-xs text-gray-600 mt-1 line-clamp-2">{{ $news->excerpt }}</p>
                            <div class="flex items-center space-x-3 mt-2">
                                <span class="inline-flex items-center text-xs text-gray-500">
                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ $news->published_at->diffForHumans() }}
                                </span>
                                <span class="inline-flex items-center text-xs text-gray-500">
                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    {{ method_exists($news, 'views') ? $news->views()->count() : (int) ($news->views ?? 0) }}
                                    views
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mb-3">
                    <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
                <p class="text-gray-500">Belum ada berita terbaru</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Complaints -->
    <div>
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-900">Pengaduan Terbaru</h2>
            <a href="{{ route('complaints.index') }}"
                class="text-primary-600 hover:text-primary-700 text-sm font-medium">
                Lihat Semua →
            </a>
        </div>
        <div class="space-y-4">
            @forelse(\App\Models\Complaint::where('is_public', true)->latest()->take(3)->get() as $complaint)
            <div class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-md transition-shadow">
                <a href="{{ route('complaints.show', $complaint) }}" class="block">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900 line-clamp-1">{{ $complaint->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-2 mt-1">{{ $complaint->description }}</p>
                            <div class="flex items-center gap-4 mt-3">
                                <span class="text-xs text-gray-500">
                                    {{ $complaint->created_at->diffForHumans() }}
                                </span>
                                <span
                                    class="text-xs px-2 py-1 bg-{{ $complaint->status === 'resolved' ? 'success' : ($complaint->status === 'in_progress' ? 'warning' : 'gray') }}-100 text-{{ $complaint->status === 'resolved' ? 'success' : ($complaint->status === 'in_progress' ? 'warning' : 'gray') }}-700 rounded-full">
                                    {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="text-center py-8">
                <p class="text-gray-500">Belum ada pengaduan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    function weatherWidget() {
    return {
        weather: {
            temp: '--',
            feels_like: '--',
            condition: 'Memuat...',
            icon: '',
            humidity: '--',
            uv: '0',
            air_quality: '--',
            air_quality_text: '--'
        },

        async init() {
            try {
                const weatherResp = await fetch('{{ route("weather.proxy") }}');
                const w = await weatherResp.json();

                const cc = w.currentConditions || {};
                this.weather.temp = Number.isFinite(cc.temperature) ? Math.round(cc.temperature) : '--';
                this.weather.feels_like = Number.isFinite(cc.temperatureApparent) ? Math.round(cc.temperatureApparent) : '--';
                this.weather.condition = cc.weatherCondition?.text || 'Kondisi terkini';
                this.weather.humidity = cc.humidity ?? '--';
                this.weather.uv = cc.uvIndex ?? '0';
                this.weather.icon = this.getWeatherIcon(null, null, this.weather.condition);

                // Air quality (optional)
                try {
                    const airResp = await fetch('{{ route("air.proxy") }}');
                    const a = await airResp.json();
                    const idx = (a.indexes || []).find(i => ((i.name || i.code || '') + '').toLowerCase().includes('universal')) || (a.indexes || [])[0];
                    const aqi = idx && typeof idx.aqi !== 'undefined' ? Math.round(idx.aqi) : null;
                    this.weather.air_quality = aqi ?? '--';
                    this.weather.air_quality_text = this.getAirQualityText(aqi);
                } catch (e) {
                    // ignore air errors
                }
            } catch (error) {
                console.error('Failed to fetch weather:', error);
                this.weather.condition = 'Data tidak tersedia';
            }
        },

        getWeatherIcon(code, isDay, text) {
            // Prefer code mapping if provided; fallback to text keywords
            const iconsByCode = {
                1000: '☀️', // Clear
                1003: '⛅',
                1006: '☁️',
                1009: '☁️',
                1030: '🌫️',
                1063: '🌦️',
                1066: '🌨️',
                1069: '🌨️',
                1072: '🌧️',
                1087: '⛈️',
                1114: '🌨️',
                1117: '🌨️',
                1135: '🌫️',
                1147: '🌫️',
                1150: '🌧️',
                1153: '🌧️',
                1168: '🌧️',
                1171: '🌧️',
                1180: '🌧️',
                1183: '🌧️',
                1186: '🌧️',
                1189: '🌧️',
                1192: '🌧️',
                1195: '🌧️',
                1198: '🌧️',
                1201: '🌧️',
                1204: '🌨️',
                1207: '🌨️',
                1210: '🌨️',
                1213: '🌨️',
                1216: '🌨️',
                1219: '🌨️',
                1222: '🌨️',
                1225: '🌨️',
                1237: '🌨️',
                1240: '🌧️',
                1243: '🌧️',
                1246: '🌧️',
                1249: '🌨️',
                1252: '🌨️',
                1255: '🌨️',
                1258: '🌨️',
                1261: '🌨️',
                1264: '🌨️',
                1273: '⛈️',
                1276: '⛈️',
                1279: '⛈️',
                1282: '⛈️',
            };
            if (code && iconsByCode[code]) return iconsByCode[code];
            const t = (text || '').toLowerCase();
            if (t.includes('hujan')) return '🌧️';
            if (t.includes('petir') || t.includes('badai')) return '⛈️';
            if (t.includes('kabut')) return '🌫️';
            if (t.includes('salju')) return '🌨️';
            if (t.includes('berawan')) return '☁️';
            if (t.includes('cerah')) return '☀️';
            return '🌤️';
        },

        getAirQualityText(value) {
            if (!value && value !== 0) return '--';
            if (value <= 50) return 'Baik';
            if (value <= 100) return 'Sedang';
            if (value <= 150) return 'Kurang Sehat';
            if (value <= 200) return 'Tidak Sehat';
            if (value <= 300) return 'Sangat Tidak Sehat';
            return 'Berbahaya';
        }
    }
}
</script>
@endpush

@push('styles')
<style>
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }

    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>
@endpush
@endsection