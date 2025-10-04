@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('header', 'Pengaturan')

@section('content')
<div class="max-w-4xl">
    <!-- System Information -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-primary-600 to-accent-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white">Informasi Sistem</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Aplikasi</label>
                    <div class="text-lg font-semibold text-gray-900">{{ $settings['app_name'] }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL Aplikasi</label>
                    <div class="text-lg font-semibold text-gray-900">{{ $settings['app_url'] }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode Maintenance</label>
                    <div>
                        @if($settings['maintenance_mode'])
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            Aktif
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Nonaktif
                        </span>
                        @endif
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Registrasi</label>
                    <div>
                        @if($settings['registration_enabled'])
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Aktif
                        </span>
                        @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Nonaktif
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Complaint Settings -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Pengaturan Pengaduan</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Auto Close Pengaduan</label>
                    <div class="flex items-center">
                        <span
                            class="text-2xl font-bold text-primary-600">{{ $settings['complaint_auto_close_days'] }}</span>
                        <span class="ml-2 text-sm text-gray-600">hari setelah selesai</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Pengaduan akan otomatis ditutup setelah periode ini</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Total Kategori Pengaduan</label>
                    <div class="text-2xl font-bold text-gray-900">{{ \App\Models\ComplaintCategory::count() }}</div>
                </div>

                <div>
                    <a href="{{ route('admin.categories.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Kelola Kategori
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Database Statistics -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Statistik Database</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ \App\Models\User::count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Pengguna</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600">{{ \App\Models\Complaint::count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Pengaduan</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ \App\Models\Application::count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Aplikasi</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ \App\Models\News::count() }}</div>
                    <div class="text-sm text-gray-600 mt-1">Total Berita</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Server Information -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Server</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">PHP Version</span>
                    <span class="text-sm text-gray-900">{{ phpversion() }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">Laravel Version</span>
                    <span class="text-sm text-gray-900">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <span class="text-sm font-medium text-gray-700">Environment</span>
                    <span class="text-sm text-gray-900">{{ app()->environment() }}</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-sm font-medium text-gray-700">Timezone</span>
                    <span class="text-sm text-gray-900">{{ config('app.timezone') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button type="button" onclick="alert('Fitur ini akan segera tersedia')"
                    class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Backup Database</span>
                </button>

                <button type="button" onclick="alert('Fitur ini akan segera tersedia')"
                    class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Clear Cache</span>
                </button>

                <button type="button" onclick="alert('Fitur ini akan segera tersedia')"
                    class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Generate Report</span>
                </button>

                <a href="{{ route('admin.reports') }}"
                    class="flex items-center justify-center px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Lihat Laporan</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
