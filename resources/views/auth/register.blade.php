@extends('layouts.guest')

@section('title', 'Registrasi')

@section('header')
<h2 class="mt-6 text-center text-2xl font-bold text-gray-900">
    Buat akun baru
</h2>
<p class="mt-2 text-center text-sm text-gray-600">
    Atau
    <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500">
        masuk ke akun yang sudah ada
    </a>
</p>
@endsection

@section('content')
<form class="space-y-6" action="{{ route('register') }}" method="POST">
    @csrf

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">
            Nama Lengkap
        </label>
        <div class="mt-1">
            <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-300 @enderror">
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">
            Email
        </label>
        <div class="mt-1">
            <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-300 @enderror">
            @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">
            Nomor Telepon
        </label>
        <div class="mt-1">
            <input id="phone" name="phone" type="tel" autocomplete="tel" required value="{{ old('phone') }}"
                placeholder="08xxxxxxxxxx"
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('phone') border-red-300 @enderror">
            @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="category" class="block text-sm font-medium text-gray-700">
            Kategori Masyarakat
        </label>
        <div class="mt-1">
            <select id="category" name="category" required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('category') border-red-300 @enderror">
                <option value="">Pilih kategori</option>
                <option value="pelajar" {{ old('category') == 'pelajar' ? 'selected' : '' }}>Pelajar</option>
                <option value="pegawai" {{ old('category') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                <option value="pencaker" {{ old('category') == 'pencaker' ? 'selected' : '' }}>Pencari Kerja</option>
                <option value="wirausaha" {{ old('category') == 'wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                <option value="umum" {{ old('category') == 'umum' ? 'selected' : '' }}>Umum</option>
            </select>
            @error('category')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">
            Password
        </label>
        <div class="mt-1">
            <input id="password" name="password" type="password" autocomplete="new-password" required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-300 @enderror">
            @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
        </div>
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
            Konfirmasi Password
        </label>
        <div class="mt-1">
            <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500">
        </div>
    </div>

    <div>
        <div class="flex items-start">
            <input id="terms" name="terms" type="checkbox" required
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
            <label for="terms" class="ml-2 block text-sm text-gray-900">
                Saya setuju dengan <a href="#" class="text-primary-600 hover:text-primary-500">Syarat dan Ketentuan</a>
                serta
                <a href="#" class="text-primary-600 hover:text-primary-500">Kebijakan Privasi</a>
            </label>
        </div>
        @error('terms')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <button type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            Daftar
        </button>
    </div>
</form>
@endsection