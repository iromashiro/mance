@extends('layouts.admin')

@section('title', 'Detail Pengguna')

@section('header', 'Detail Pengguna')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            ← Kembali ke Daftar Pengguna
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}"
                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                Edit Data
            </a>
            @if($user->banned_at)
            <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Aktifkan Akun
                </button>
            </form>
            @else
            <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" class="inline"
                onsubmit="return confirm('Yakin ingin menonaktifkan akun ini?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Nonaktifkan Akun
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex flex-col items-center">
                    <div
                        class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center mb-4">
                        <span class="text-white text-3xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>

                    @if($user->banned_at)
                    <span class="mt-3 px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                        Akun Dinonaktifkan
                    </span>
                    @else
                    <span class="mt-3 px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                        Akun Aktif
                    </span>
                    @endif
                </div>

                <div class="mt-6 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Kategori:</span>
                        <span class="font-medium capitalize">
                            {{ str_replace('_', ' ', $user->category) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Telepon:</span>
                        <span class="font-medium">{{ $user->phone ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Bergabung:</span>
                        <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    @if($user->email_verified_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Email Verified:</span>
                        <span class="text-green-600">✓ Terverifikasi</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="bg-gradient-to-br from-primary-600 to-primary-800 shadow rounded-lg p-6 mt-6 text-white">
                <h3 class="text-lg font-semibold mb-4">Statistik Cepat</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-primary-100">Total Pengaduan</span>
                        <span class="text-2xl font-bold">{{ $stats['total_complaints'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-primary-100">Aplikasi Favorit</span>
                        <span class="text-2xl font-bold">{{ $stats['favorite_apps'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-primary-100">Total Aktivitas</span>
                        <span class="text-2xl font-bold">{{ $stats['total_activities'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Complaint Stats -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Status Pengaduan</h3>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-3xl font-bold text-yellow-600">{{ $stats['pending_complaints'] }}</div>
                        <div class="text-sm text-gray-600 mt-1">Pending</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-3xl font-bold text-blue-600">
                            {{ $stats['total_complaints'] - $stats['pending_complaints'] - $stats['completed_complaints'] }}
                        </div>
                        <div class="text-sm text-gray-600 mt-1">Diproses</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-3xl font-bold text-green-600">{{ $stats['completed_complaints'] }}</div>
                        <div class="text-sm text-gray-600 mt-1">Selesai</div>
                    </div>
                </div>
            </div>

            <!-- Recent Complaints -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Pengaduan Terbaru</h3>
                @if($recentComplaints->count() > 0)
                <div class="space-y-3">
                    @foreach($recentComplaints as $complaint)
                    <a href="{{ route('admin.complaints.show', $complaint) }}"
                        class="block p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-mono text-gray-500">{{ $complaint->ticket_number }}</span>
                                    <span
                                        class="px-2 py-0.5 text-xs rounded-full
                                        {{ $complaint->status == 'completed' ? 'bg-green-100 text-green-800' :
                                           ($complaint->status == 'process' ? 'bg-blue-100 text-blue-800' :
                                           ($complaint->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst($complaint->status) }}
                                    </span>
                                </div>
                                <h4 class="font-medium text-gray-900">{{ $complaint->title }}</h4>
                                <p class="text-sm text-gray-500 mt-1">{{ $complaint->category->name }}</p>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">{{ $complaint->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 py-8">Belum ada pengaduan</p>
                @endif
            </div>

            <!-- Recent Activities -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>
                @if($recentActivities->count() > 0)
                <div class="space-y-3">
                    @foreach($recentActivities as $activity)
                    <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-2 h-2 bg-primary-600 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm text-gray-900">{{ $activity->activity_type }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-gray-500 py-8">Belum ada aktivitas tercatat</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
