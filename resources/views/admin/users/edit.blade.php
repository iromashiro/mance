@extends('layouts.admin')

@section('title', 'Edit Pengguna')

@section('header', 'Edit Pengguna')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Data Pengguna</h1>
        <a href="{{ route('admin.users.show', $user) }}"
            class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            ← Kembali
        </a>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Telepon
                </label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                    placeholder="Contoh: 08123456789"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                @error('phone')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="category" id="category" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="pelajar" {{ old('category', $user->category) == 'pelajar' ? 'selected' : '' }}>
                        Pelajar/Mahasiswa
                    </option>
                    <option value="pegawai" {{ old('category', $user->category) == 'pegawai' ? 'selected' : '' }}>
                        Pegawai
                    </option>
                    <option value="pencari_kerja"
                        {{ old('category', $user->category) == 'pencari_kerja' ? 'selected' : '' }}>
                        Pencari Kerja
                    </option>
                    <option value="pengusaha" {{ old('category', $user->category) == 'pengusaha' ? 'selected' : '' }}>
                        Pengusaha
                    </option>
                </select>
                @error('category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Account Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', !$user->banned_at) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm text-gray-700">Akun Aktif</span>
                </label>
                <p class="mt-1 text-sm text-gray-500">
                    Jika dinonaktifkan, pengguna tidak akan bisa login
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold mb-1">Informasi Akun:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Bergabung: {{ $user->created_at->format('d M Y, H:i') }}</li>
                            <li>Terakhir update: {{ $user->updated_at->format('d M Y, H:i') }}</li>
                            @if($user->email_verified_at)
                            <li>Email terverifikasi: {{ $user->email_verified_at->format('d M Y') }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('admin.users.show', $user) }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <!-- Reset Password Section -->
    <div class="bg-white shadow rounded-lg p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Reset Password</h3>
        <form action="{{ route('admin.users.resetPassword', $user) }}" method="POST"
            onsubmit="return confirm('Yakin ingin mereset password pengguna ini?')" x-data="{ showPassword: false }">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Minimal 8 karakter">
                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            <svg x-show="showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" style="display:none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Ketik ulang password baru">
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm text-yellow-800">
                        ⚠️ Pengguna akan menerima notifikasi berisi password baru mereka. Pastikan untuk
                        menginformasikan
                        kepada pengguna bahwa password mereka telah direset.
                    </p>
                </div>

                <button type="submit"
                    class="w-full px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                    Reset Password
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white shadow rounded-lg p-6 mt-6 border-2 border-red-200">
        <h3 class="text-lg font-semibold text-red-600 mb-4">Zona Bahaya</h3>
        <p class="text-sm text-gray-600 mb-4">
            Tindakan ini bersifat permanen dan tidak dapat dibatalkan. Semua data pengaduan dan aktivitas pengguna akan
            hilang.
        </p>
        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
            onsubmit="return confirm('PERHATIAN! Yakin ingin menghapus pengguna ini? Tindakan ini tidak dapat dibatalkan!')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                Hapus Pengguna Permanen
            </button>
        </form>
    </div>
</div>
@endsection
