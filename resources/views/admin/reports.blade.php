@extends('layouts.admin')

@section('title', 'Laporan')

@section('header', 'Laporan')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Laporan & Statistik Sistem</h1>

    <!-- Date Range Filter -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.reports') }}" class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date"
                    value="{{ request('start_date', now()->subDays(30)->format('Y-m-d')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date"
                    value="{{ request('end_date', now()->format('Y-m-d')) }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    Filter
                </button>
                <a href="{{ route('admin.reports') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Overview Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 shadow rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Pengguna</p>
                    <p class="text-3xl font-bold mt-1">{{ $users }}</p>
                </div>
                <div class="bg-white bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 shadow rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Total Aplikasi</p>
                    <p class="text-3xl font-bold mt-1">{{ $applications }}</p>
                </div>
                <div class="bg-white bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 shadow rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Berita</p>
                    <p class="text-3xl font-bold mt-1">{{ $news }}</p>
                </div>
                <div class="bg-white bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 shadow rounded-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Total Pengaduan</p>
                    <p class="text-3xl font-bold mt-1">{{ $complaints }}</p>
                </div>
                <div class="bg-white bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaints by Status -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaduan Berdasarkan Status</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($complaintsByStatus as $status)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">
                    @if($status->status == 'pending') Menunggu
                    @elseif($status->status == 'process') Diproses
                    @elseif($status->status == 'completed') Selesai
                    @elseif($status->status == 'rejected') Ditolak
                    @endif
                </p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $status->total }}</p>
                <div class="mt-2 bg-gray-200 rounded-full h-2">
                    <div class="bg-primary-600 rounded-full h-2"
                        style="width: {{ $complaints > 0 ? ($status->total / $complaints * 100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Complaints by Priority -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaduan Berdasarkan Prioritas</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach($complaintsByPriority as $priority)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-500">
                    @if($priority->priority == 'low') Rendah
                    @elseif($priority->priority == 'medium') Sedang
                    @elseif($priority->priority == 'high') Tinggi
                    @endif
                </p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $priority->total }}</p>
                <div class="mt-2 rounded-full h-2"
                    style="background: {{ $priority->priority == 'high' ? '#fee2e2' : ($priority->priority == 'medium' ? '#fef3c7' : '#dcfce7') }}">
                    <div class="rounded-full h-2"
                        style="width: {{ $complaints > 0 ? ($priority->total / $complaints * 100) : 0 }}%; background: {{ $priority->priority == 'high' ? '#dc2626' : ($priority->priority == 'medium' ? '#f59e0b' : '#16a34a') }}">
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengguna Terbaru ({{ request('start_date') }} -
                {{ request('end_date') }})</h2>
            @if($recentUsers->count() > 0)
            <div class="space-y-3">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-primary-600 text-sm font-semibold">
                                {{ substr($user->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500">
                        {{ $user->created_at->diffForHumans() }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-center text-gray-500 py-8">Tidak ada pengguna baru</p>
            @endif
        </div>

        <!-- Recent Complaints -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaduan Terbaru ({{ request('start_date') }} -
                {{ request('end_date') }})</h2>
            @if($recentComplaints->count() > 0)
            <div class="space-y-3">
                @foreach($recentComplaints as $complaint)
                <a href="{{ route('admin.complaints.show', $complaint) }}"
                    class="block py-2 border-b border-gray-100 last:border-0 hover:bg-gray-50 -mx-2 px-2 rounded">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ Str::limit($complaint->title, 40) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $complaint->user->name }}</p>
                        </div>
                        <span
                            class="px-2 py-1 text-xs rounded-full
                            {{ $complaint->status == 'completed' ? 'bg-green-100 text-green-800' :
                               ($complaint->status == 'process' ? 'bg-blue-100 text-blue-800' :
                               ($complaint->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($complaint->status) }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <p class="text-center text-gray-500 py-8">Tidak ada pengaduan baru</p>
            @endif
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-900 mb-1">Export Laporan</h3>
                <p class="text-sm text-blue-700">Download data untuk analisis lebih lanjut</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.complaints.export') }}"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                    Export Pengaduan (CSV)
                </a>
                <a href="{{ route('admin.users.export') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                    Export Pengguna (CSV)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
