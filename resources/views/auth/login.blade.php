@extends('layouts.guest')

@section('title', 'Masuk')

@section('header')
<div class="mt-6 text-center">
    <div class="inline-flex items-center px-3 py-1 rounded-full bg-primary-100 text-primary-800 text-xs font-medium">
        Selamat Datang Kembali
    </div>
    <h2 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight">
        <span class="bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
            Masuk ke akun MANCE
        </span>
    </h2>
    <p class="mt-2 text-sm text-gray-600">
        Satu akun untuk semua layanan Smart City Muara Enim
    </p>
    <div class="mt-4">
        <a href="{{ route('onboarding') }}"
            class="inline-flex items-center text-xs sm:text-sm font-medium text-primary-600 hover:text-primary-700">
            Pelajari Aplikasi
            <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>
@endsection

@section('content')
<div x-data="{ showPassword: false, caps: false }" class="space-y-6">
    {{-- Error summary --}}
    @if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    <form class="space-y-5" action="{{ route('login') }}" method="POST">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12H8m8 0a8 8 0 11-16 0 8 8 0 0116 0zM12 12v.01"></path>
                    </svg>
                </span>
                <input id="email" name="email" type="email" inputmode="email" autocomplete="email" required
                    value="{{ old('email') }}"
                    class="block w-full pl-10 pr-3 py-2.5 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/60 focus:border-primary-500 @error('email') border-red-300 focus:ring-red-300 @enderror">
            </div>
            @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <span x-show="caps" x-cloak class="text-xs text-amber-600">Caps Lock aktif</span>
            </div>
            <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.4 15a8 8 0 10-14.8 0"></path>
                    </svg>
                </span>
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                    autocomplete="current-password" required
                    @keyup.capture="caps = $event.getModifierState && $event.getModifierState('CapsLock')"
                    class="block w-full pl-10 pr-10 py-2.5 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/60 focus:border-primary-500 @error('password') border-red-300 focus:ring-red-300 @enderror">
                <button type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                    @click="showPassword = !showPassword" aria-label="Toggle password">
                    <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 013.043-4.5M9.88 9.88a3 3 0 104.24 4.24" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6.1 6.1l11.8 11.8M10.6 5.06A9.967 9.967 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.98 9.98 0 01-2.342 3.944" />
                    </svg>
                </button>
            </div>
            @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember --}}
        <div class="flex items-center justify-between">
            <label class="inline-flex items-center">
                <input id="remember" name="remember" type="checkbox"
                    class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <span class="ml-2 text-sm text-gray-900">Ingat saya</span>
            </label>
            {{-- Password reset disabled --}}
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit"
                class="group w-full inline-flex items-center justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white
                           bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                <svg class="h-5 w-5 mr-2 opacity-90 group-hover:translate-x-0.5 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h13M12 5l7 7-7 7">
                    </path>
                </svg>
                Masuk
            </button>
        </div>
    </form>

    {{-- Footer actions --}}
    <div class="space-y-6">
        <div class="relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t border-gray-200"></div>
            </div>
            <div class="relative flex justify-center">
                <span class="px-3 bg-white text-xs text-gray-500">Belum punya akun?</span>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('register') }}"
                class="inline-flex items-center text-sm font-semibold text-primary-600 hover:text-primary-700">
                Daftar akun baru
                <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>

        {{-- Demo box --}}
        <div class="rounded-2xl border border-gray-100 bg-gradient-to-br from-gray-50 to-white p-4 ring-1 ring-black/5">
            <div class="flex items-start">
                <div class="mr-3 mt-0.5">
                    <span
                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-primary-100 text-primary-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                </div>
                <div class="text-xs sm:text-sm text-gray-700">
                    <p class="font-semibold text-gray-900 mb-1">Akun Demo</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="rounded-lg bg-white p-3 border border-gray-100">
                            <p class="text-gray-600"><strong>Admin:</strong></p>
                            <p class="text-gray-600 break-all">admin@mance.go.id</p>
                            <p class="text-gray-600">password123</p>
                        </div>
                        <div class="rounded-lg bg-white p-3 border border-gray-100">
                            <p class="text-gray-600"><strong>User Demo:</strong></p>
                            <p class="text-gray-600 break-all">budi.santoso@email.com</p>
                            <p class="text-gray-600">password</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
