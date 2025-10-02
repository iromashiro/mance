@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('header', 'Dashboard')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
    <!-- Total Users -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pengguna</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\User::count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('admin.users.index') }}" class="font-medium text-primary-600 hover:text-primary-900">
                    Lihat semua
                </a>
            </div>
        </div>
    </div>

    <!-- Total Complaints -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Pengaduan</dt>
                        <dd class="text-lg font-semibold text-gray-900">{{ \App\Models\Complaint::count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('admin.complaints.index') }}"
                    class="font-medium text-primary-600 hover:text-primary-900">
                    Lihat semua
                </a>
            </div>
        </div>
    </div>

    <!-- Active Applications -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Layanan Aktif</dt>
                        <dd class="text-lg font-semibold text-gray-900">
                            {{ \App\Models\Application::where('status', 'active')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('admin.applications.index') }}"
                    class="font-medium text-primary-600 hover:text-primary-900">
                    Kelola layanan
                </a>
            </div>
        </div>
    </div>

    <!-- Published News -->
    <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-gray-500 truncate">Berita Dipublikasi</dt>
                        <dd class="text-lg font-semibold text-gray-900">
                            {{ \App\Models\News::where('status', 'published')->count() }}</dd>
                    </dl>
                </div>
            </div>
        </div>
        <div class="bg-gray-50 px-5 py-3">
            <div class="text-sm">
                <a href="{{ route('admin.news.index') }}" class="font-medium text-primary-600 hover:text-primary-900">
                    Kelola berita
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="mt-8 grid grid-cols-1 gap-5 lg:grid-cols-2">
    <!-- Complaint Status Chart -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Status Pengaduan</h3>
        @php
        $complaintStats = \App\Models\Complaint::selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->pluck('count', 'status');
        @endphp
        <div class="space-y-3">
            <div class="flex items-center">
                <span class="text-sm font-medium text-gray-500 w-24">Menunggu</span>
                <div class="flex-1 bg-gray-200 rounded-full h-6 ml-2">
                    <div class="bg-gray-500 h-6 rounded-full text-xs text-white flex items-center justify-center"
                        style="width: {{ $complaintStats->sum() > 0 ? ($complaintStats->get('pending', 0) / $complaintStats->sum() * 100) : 0 }}%">
                        {{ $complaintStats->get('pending', 0) }}
                    </div>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-sm font-medium text-gray-500 w-24">Diproses</span>
                <div class="flex-1 bg-gray-200 rounded-full h-6 ml-2">
                    <div class="bg-yellow-500 h-6 rounded-full text-xs text-white flex items-center justify-center"
                        style="width: {{ $complaintStats->sum() > 0 ? ($complaintStats->get('process', 0) / $complaintStats->sum() * 100) : 0 }}%">
                        {{ $complaintStats->get('process', 0) }}
                    </div>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-sm font-medium text-gray-500 w-24">Selesai</span>
                <div class="flex-1 bg-gray-200 rounded-full h-6 ml-2">
                    <div class="bg-green-500 h-6 rounded-full text-xs text-white flex items-center justify-center"
                        style="width: {{ $complaintStats->sum() > 0 ? ($complaintStats->get('completed', 0) / $complaintStats->sum() * 100) : 0 }}%">
                        {{ $complaintStats->get('completed', 0) }}
                    </div>
                </div>
            </div>
            <div class="flex items-center">
                <span class="text-sm font-medium text-gray-500 w-24">Ditolak</span>
                <div class="flex-1 bg-gray-200 rounded-full h-6 ml-2">
                    <div class="bg-red-500 h-6 rounded-full text-xs text-white flex items-center justify-center"
                        style="width: {{ $complaintStats->sum() > 0 ? ($complaintStats->get('rejected', 0) / $complaintStats->sum() * 100) : 0 }}%">
                        {{ $complaintStats->get('rejected', 0) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- User Categories Chart -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Kategori Pengguna</h3>
        @php
        $userCategories = \App\Models\User::where('role', 'masyarakat')
        ->selectRaw('category, count(*) as count')
        ->groupBy('category')
        ->pluck('count', 'category');
        @endphp
        <div class="space-y-3">
            @foreach(['pelajar', 'pegawai', 'pencaker', 'wirausaha', 'umum'] as $category)
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700 capitalize">{{ $category }}</span>
                <span class="text-sm text-gray-500">{{ $userCategories->get($category, 0) }} pengguna</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="mt-8">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Aktivitas Terbaru</h3>
            <div class="flow-root">
                <ul class="-mb-8">
                    @php
                    $recentComplaints = \App\Models\Complaint::with('user')->latest()->take(5)->get();
                    @endphp
                    @foreach($recentComplaints as $index => $complaint)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3">
                                <div>
                                    <span
                                        class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center ring-8 ring-white">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                            </path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-500">
                                            Pengaduan baru dari <span
                                                class="font-medium text-gray-900">{{ $complaint->user->name }}</span>
                                        </p>
                                        <p class="text-sm text-gray-400">{{ Str::limit($complaint->description, 50) }}
                                        </p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        {{ $complaint->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection