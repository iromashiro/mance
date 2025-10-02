@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-lg shadow-lg p-6 mb-6">
        <h1 class="text-2xl font-bold text-white">
            Selamat Datang, {{ Auth::user()->name }}!
        </h1>
        <p class="text-primary-100 mt-2">
            @if(Auth::user()->category == 'pelajar')
            Akses layanan khusus pelajar dan informasi pendidikan
            @elseif(Auth::user()->category == 'pegawai')
            Akses layanan kepegawaian dan informasi penting
            @elseif(Auth::user()->category == 'pencaker')
            Temukan lowongan kerja dan pelatihan
            @elseif(Auth::user()->category == 'wirausaha')
            Akses layanan UMKM dan informasi bisnis
            @else
            Akses semua layanan publik Muara Enim
            @endif
        </p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pengaduan Aktif</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ Auth::user()->complaints()->whereIn('status', ['pending', 'process'])->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pengaduan Selesai</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ Auth::user()->complaints()->where('status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Notifikasi Baru</p>
                    <p class="text-2xl font-semibold text-gray-900">
                        {{ Auth::user()->notifications()->where('is_read', false)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('complaints.create') }}"
                class="flex flex-col items-center p-4 bg-primary-50 rounded-lg hover:bg-primary-100 transition">
                <svg class="h-8 w-8 text-primary-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-sm font-medium text-gray-900">Buat Pengaduan</span>
            </a>

            <a href="{{ route('applications.index') }}"
                class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <svg class="h-8 w-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                    </path>
                </svg>
                <span class="text-sm font-medium text-gray-900">Layanan</span>
            </a>

            <a href="{{ route('news.index') }}"
                class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <svg class="h-8 w-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                <span class="text-sm font-medium text-gray-900">Berita</span>
            </a>

            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition">
                <svg class="h-8 w-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-900">Profil</span>
            </a>
        </div>
    </div>

    <!-- Recent Complaints & News -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Complaints -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Pengaduan Terbaru Anda</h2>
                <a href="{{ route('complaints.index') }}" class="text-sm text-primary-600 hover:text-primary-800">Lihat
                    Semua</a>
            </div>
            @if(Auth::user()->complaints()->count() > 0)
            <div class="space-y-3">
                @foreach(Auth::user()->complaints()->latest()->take(3)->get() as $complaint)
                <div
                    class="border-l-4 border-{{ $complaint->status == 'completed' ? 'green' : ($complaint->status == 'process' ? 'yellow' : 'gray') }}-400 pl-4 py-2">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $complaint->ticket_number }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($complaint->description, 50) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $complaint->created_at->diffForHumans() }}</p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs font-medium rounded-full
                                    {{ $complaint->status == 'completed' ? 'bg-green-100 text-green-800' :
                                       ($complaint->status == 'process' ? 'bg-yellow-100 text-yellow-800' :
                                       ($complaint->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Belum ada pengaduan</p>
            @endif
        </div>

        <!-- Recent News -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Berita Terkini</h2>
                <a href="{{ route('news.index') }}" class="text-sm text-primary-600 hover:text-primary-800">Lihat
                    Semua</a>
            </div>
            @php
            $recentNews = \App\Models\News::where('status', 'published')->latest()->take(3)->get();
            @endphp
            @if($recentNews->count() > 0)
            <div class="space-y-3">
                @foreach($recentNews as $news)
                <a href="{{ route('news.show', $news->id) }}" class="block hover:bg-gray-50 -mx-2 px-2 py-2 rounded">
                    <div class="flex space-x-3">
                        @if($news->image_url)
                        <img src="{{ Storage::url($news->image_url) }}" alt="{{ $news->title }}"
                            class="h-16 w-16 object-cover rounded">
                        @else
                        <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-gray-900">{{ $news->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ Str::limit($news->excerpt, 60) }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $news->published_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <p class="text-gray-500 text-sm">Belum ada berita</p>
            @endif
        </div>
    </div>
</div>
@endsection