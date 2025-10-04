@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('header', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-primary-600 to-accent-600 rounded-2xl shadow-xl p-8 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h1>
            <p class="text-white/80 text-lg">Berikut adalah ringkasan sistem hari ini</p>
            <p class="text-white/60 text-sm mt-2">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-white/20 backdrop-blur-lg rounded-2xl p-6">
                <div class="text-4xl font-bold">{{ now()->format('H:i') }}</div>
                <div class="text-sm text-white/80 mt-1">WIB</div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Users -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">
                    +{{ \App\Models\User::where('role', 'masyarakat')->whereMonth('created_at', now()->month)->count() }}
                    baru
                </span>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium mb-1">Total Pengguna</h3>
                <p class="text-3xl font-bold text-gray-900">
                    {{ number_format(\App\Models\User::where('role', 'masyarakat')->count()) }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-3 border-t border-blue-200">
            <a href="{{ route('admin.users.index') }}"
                class="text-sm font-semibold text-blue-600 hover:text-blue-800 flex items-center">
                Lihat Detail
                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Total Complaints -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">
                    {{ \App\Models\Complaint::where('status', 'pending')->count() }} pending
                </span>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium mb-1">Total Pengaduan</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format(\App\Models\Complaint::count()) }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 px-6 py-3 border-t border-yellow-200">
            <a href="{{ route('admin.complaints.index') }}"
                class="text-sm font-semibold text-yellow-600 hover:text-yellow-800 flex items-center">
                Lihat Detail
                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Active Applications -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full">
                    {{ \App\Models\Application::where('is_active', true)->count() }} aktif
                </span>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium mb-1">Total Layanan</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format(\App\Models\Application::count()) }}</p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-3 border-t border-green-200">
            <a href="{{ route('admin.applications.index') }}"
                class="text-sm font-semibold text-green-600 hover:text-green-800 flex items-center">
                Lihat Detail
                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Published News -->
    <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                    {{ \App\Models\News::where('is_published', false)->count() }} draft
                </span>
            </div>
            <div>
                <h3 class="text-gray-500 text-sm font-medium mb-1">Berita Publish</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format(\App\Models\News::published()->count()) }}
                </p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-3 border-t border-purple-200">
            <a href="{{ route('admin.news.index') }}"
                class="text-sm font-semibold text-purple-600 hover:text-purple-800 flex items-center">
                Lihat Detail
                <svg class="h-4 w-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Complaint Status Chart -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <svg class="h-5 w-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                    </path>
                </svg>
                Status Pengaduan
            </h3>
        </div>
        @php
        $complaintStats = \App\Models\Complaint::selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');
        $total = $complaintStats->sum();
        @endphp
        <div class="p-6 space-y-4">
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700 flex items-center">
                        <span class="h-3 w-3 rounded-full bg-gray-500 mr-2"></span>
                        Menunggu
                    </span>
                    <span class="text-sm font-bold text-gray-900">{{ $complaintStats->get('pending', 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-400 to-gray-500 h-3 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                        style="width: {{ $total > 0 ? ($complaintStats->get('pending', 0) / $total * 100) : 0 }}%">
                        @if($total > 0 && ($complaintStats->get('pending', 0) / $total * 100) > 10)
                        <span
                            class="text-xs text-white font-semibold">{{ number_format($complaintStats->get('pending', 0) / $total * 100, 1) }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700 flex items-center">
                        <span class="h-3 w-3 rounded-full bg-yellow-500 mr-2"></span>
                        Diproses
                    </span>
                    <span class="text-sm font-bold text-gray-900">{{ $complaintStats->get('process', 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 h-3 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                        style="width: {{ $total > 0 ? ($complaintStats->get('process', 0) / $total * 100) : 0 }}%">
                        @if($total > 0 && ($complaintStats->get('process', 0) / $total * 100) > 10)
                        <span
                            class="text-xs text-white font-semibold">{{ number_format($complaintStats->get('process', 0) / $total * 100, 1) }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700 flex items-center">
                        <span class="h-3 w-3 rounded-full bg-green-500 mr-2"></span>
                        Selesai
                    </span>
                    <span class="text-sm font-bold text-gray-900">{{ $complaintStats->get('completed', 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-400 to-green-500 h-3 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                        style="width: {{ $total > 0 ? ($complaintStats->get('completed', 0) / $total * 100) : 0 }}%">
                        @if($total > 0 && ($complaintStats->get('completed', 0) / $total * 100) > 10)
                        <span
                            class="text-xs text-white font-semibold">{{ number_format($complaintStats->get('completed', 0) / $total * 100, 1) }}%</span>
                        @endif
                    </div>
                </div>
            </div>

            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700 flex items-center">
                        <span class="h-3 w-3 rounded-full bg-red-500 mr-2"></span>
                        Ditolak
                    </span>
                    <span class="text-sm font-bold text-gray-900">{{ $complaintStats->get('rejected', 0) }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-red-400 to-red-500 h-3 rounded-full transition-all duration-500 flex items-center justify-end pr-2"
                        style="width: {{ $total > 0 ? ($complaintStats->get('rejected', 0) / $total * 100) : 0 }}%">
                        @if($total > 0 && ($complaintStats->get('rejected', 0) / $total * 100) > 10)
                        <span
                            class="text-xs text-white font-semibold">{{ number_format($complaintStats->get('rejected', 0) / $total * 100, 1) }}%</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Categories Chart -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <svg class="h-5 w-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                Kategori Pengguna
            </h3>
        </div>
        @php
        $userCategories = \App\Models\User::where('role', 'masyarakat')
        ->selectRaw('category, count(*) as count')
        ->groupBy('category')
        ->pluck('count', 'category');
        $colors = ['pelajar' => 'blue', 'pegawai' => 'green', 'pencari_kerja' => 'yellow', 'pengusaha' => 'purple'];
        @endphp
        <div class="p-6 space-y-4">
            @foreach(['pelajar' => 'Pelajar', 'pegawai' => 'Pegawai', 'pencari_kerja' => 'Pencari Kerja', 'pengusaha' =>
            'Pengusaha'] as $key => $label)
            <div
                class="flex items-center justify-between p-4 rounded-xl bg-{{ $colors[$key] }}-50 hover:bg-{{ $colors[$key] }}-100 transition-colors">
                <div class="flex items-center">
                    <div
                        class="h-12 w-12 rounded-xl bg-{{ $colors[$key] }}-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ $userCategories->get($key, 0) }}
                    </div>
                    <span class="ml-4 font-semibold text-gray-900">{{ $label }}</span>
                </div>
                <span class="text-sm text-gray-600">
                    {{ $userCategories->sum() > 0 ? number_format($userCategories->get($key, 0) / $userCategories->sum() * 100, 1) : 0 }}%
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <svg class="h-5 w-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Pengaduan Terbaru
            </h3>
            <a href="{{ route('admin.complaints.index') }}"
                class="text-sm font-semibold text-primary-600 hover:text-primary-800">
                Lihat Semua →
            </a>
        </div>
    </div>
    <div class="divide-y divide-gray-200">
        @php
        $recentComplaints = \App\Models\Complaint::with('user')->latest()->take(5)->get();
        @endphp
        @forelse($recentComplaints as $complaint)
        <div class="p-6 hover:bg-gray-50 transition-colors">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div
                        class="h-12 w-12 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-bold shadow-lg">
                        {{ substr($complaint->user->name, 0, 2) }}
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">{{ $complaint->title }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                Pengaduan dari <span
                                    class="font-medium text-gray-900">{{ $complaint->user->name }}</span>
                            </p>
                            <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $complaint->description }}</p>
                        </div>
                        <div class="ml-4 flex flex-col items-end space-y-2">
                            @if($complaint->status == 'pending')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                Menunggu
                            </span>
                            @elseif($complaint->status == 'process')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Diproses
                            </span>
                            @elseif($complaint->status == 'completed')
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                            @else
                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                Ditolak
                            </span>
                            @endif
                            <span
                                class="text-xs text-gray-500 whitespace-nowrap">{{ $complaint->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                </path>
            </svg>
            <p class="text-gray-500 font-medium">Belum ada pengaduan</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
