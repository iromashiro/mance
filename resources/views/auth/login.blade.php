@extends('layouts.guest')

@section('title', 'Login')

@section('header')
<h2 class="mt-6 text-center text-2xl font-bold text-gray-900">
    Masuk ke akun Anda
</h2>
<p class="mt-2 text-center text-sm text-gray-600">
    Atau
    <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
        daftar akun baru
    </a>
</p>
@endsection

@section('content')
<form class="space-y-6" action="{{ route('login') }}" method="POST">
    @csrf

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
        <label for="password" class="block text-sm font-medium text-gray-700">
            Password
        </label>
        <div class="mt-1">
            <input id="password" name="password" type="password" autocomplete="current-password" required
                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-300 @enderror">
            @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember" name="remember" type="checkbox"
                class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
            <label for="remember" class="ml-2 block text-sm text-gray-900">
                Ingat saya
            </label>
        </div>

        {{-- Password reset temporarily disabled --}}
        {{-- <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-primary-600 hover:text-primary-500">
        Lupa password?
        </a>
    </div> --}}
    </div>

    <div>
        <button type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            Masuk
        </button>
    </div>
</form>

<div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Informasi Login</span>
        </div>
    </div>

    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <p class="text-sm text-gray-600 mb-2">
            <strong>Admin:</strong><br>
            Email: admin@mance.go.id<br>
            Password: password123
        </p>
        <p class="text-sm text-gray-600">
            <strong>User Demo:</strong><br>
            Email: budi.santoso@email.com<br>
            Password: password
        </p>
    </div>
</div>
@endsection