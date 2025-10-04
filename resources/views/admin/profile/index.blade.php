@extends('layouts.admin')

@section('title', 'Profil Admin')

@section('header', 'Profil Saya')

@section('content')
<div class="max-w-4xl">
    <!-- Profile Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-primary-600 to-accent-600 h-32"></div>
        <div class="px-6 pb-6">
            <div class="relative">
                <div class="absolute -top-16 left-0">
                    <div class="h-32 w-32 rounded-full bg-white p-2 shadow-lg">
                        <div
                            class="h-full w-full rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white text-4xl font-bold">
                            {{ substr($admin->name, 0, 2) }}
                        </div>
                    </div>
                </div>
                <div class="pt-20">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $admin->name }}</h2>
                            <p class="text-sm text-gray-600 mt-1">{{ $admin->email }}</p>
                            <span
                                class="inline-block mt-2 px-3 py-1 text-xs font-semibold rounded-full bg-primary-100 text-primary-800">
                                Administrator
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Bergabung sejak</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $admin->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    @error('phone')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Ubah Password</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('admin.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="change_password">

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Password Saat
                        Ini</label>
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="new_password" id="new_password" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                </div>

                <div>
                    <label for="new_password_confirmation"
                        class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Statistics -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Statistik Aktivitas</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-3xl font-bold text-blue-600">
                        {{ \App\Models\ComplaintResponse::where('user_id', $admin->id)->count() }}
                    </div>
                    <div class="text-sm text-gray-600 mt-2">Total Tanggapan</div>
                </div>

                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-3xl font-bold text-green-600">
                        {{ \App\Models\Complaint::where('status', 'completed')->count() }}
                    </div>
                    <div class="text-sm text-gray-600 mt-2">Pengaduan Selesai</div>
                </div>

                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-3xl font-bold text-purple-600">
                        {{ \App\Models\News::where('author', $admin->name)->count() }}
                    </div>
                    <div class="text-sm text-gray-600 mt-2">Berita Dipublikasi</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
