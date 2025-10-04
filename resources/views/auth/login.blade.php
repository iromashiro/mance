@extends('layouts.guest')

@section('title', 'Masuk')

@section('header')
<div class="text-center mb-6">
    <div
        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-primary-500/10 to-accent-500/10 border border-primary-200/50 backdrop-blur-sm mb-4">
        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
        </svg>
        <span class="text-sm font-semibold text-primary-700">Selamat Datang Kembali</span>
    </div>

    <h2 class="text-3xl font-black text-gray-900 mb-2">
        Masuk ke MANCE
    </h2>
    <p class="text-gray-600">
        Akses semua layanan smart city dalam satu platform
    </p>
</div>
@endsection

@section('content')
<div x-data="{
        showPassword: false,
        capsLock: false,
        email: '{{ old('email') }}',
        password: '',
        isValid: false,
        checkValid() {
            this.isValid = this.email.length > 0 && this.password.length >= 6;
        }
    }" x-init="checkValid()" class="space-y-6">

    @if ($errors->any())
    <div class="rounded-2xl bg-red-50 border border-red-200 p-4 flex items-start gap-3" x-data="{ show: true }"
        x-show="show" x-transition>
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
        </div>
        <button @click="show = false" class="flex-shrink-0 text-red-400 hover:text-red-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
                </div>
                <input type="email" id="email" name="email" required autocomplete="email" x-model="email"
                    @input="checkValid()" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                           focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                           transition-all duration-200 outline-none @error('email') border-red-300 @enderror"
                    placeholder="nama@email.com">

                <!-- Email validation indicator -->
                <div x-show="email.length > 0" x-cloak class="absolute inset-y-0 right-0 pr-4 flex items-center">
                    <svg x-show="email.includes('@') && email.includes('.')" class="w-5 h-5 text-green-500" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            @error('email')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                    autocomplete="current-password" x-model="password" @input="checkValid()"
                    @keyup.capture="capsLock = $event.getModifierState && $event.getModifierState('CapsLock')" class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                           focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                           transition-all duration-200 outline-none @error('password') border-red-300 @enderror"
                    placeholder="••••••••">

                <!-- Toggle password visibility -->
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.98 9.98 0 01-2.342 3.944m4.217 4.051A9.935 9.935 0 0112 21c4.478 0 8.268-2.943 9.543-7a10.025 10.025 0 00-3.226-5.166M9.88 9.88a3 3 0 104.24 4.24M9.88 9.88L6.172 6.172M9.88 9.88l4.24 4.24m0 0l3.708 3.708m-7.948-3.708l-3.708-3.708" />
                    </svg>
                </button>
            </div>

            <!-- Caps Lock Warning -->
            <div x-show="capsLock" x-cloak x-transition class="mt-2 flex items-center gap-2 text-sm text-amber-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Caps Lock aktif</span>
            </div>

            @error('password')
            <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $message }}
            </p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label class="flex items-center group cursor-pointer">
                <input type="checkbox" name="remember"
                    class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2 transition-all">
                <span class="ml-2 text-sm text-gray-700 group-hover:text-gray-900">Ingat saya</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="group relative w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700
                   text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40
                   transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed
                   focus:ring-4 focus:ring-primary-300 outline-none" :disabled="!isValid">
            <span class="flex items-center justify-center gap-2">
                <span>Masuk</span>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7l5 5m0 0l-5 5m5-5H6" />
                </svg>
            </span>
        </button>
    </form>

    <!-- Divider -->
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-200"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-4 bg-white text-gray-500">Belum punya akun?</span>
        </div>
    </div>

    <!-- Register Link -->
    <a href="{{ route('register') }}" class="group flex items-center justify-center gap-2 w-full py-3.5 px-6 border-2 border-gray-200 hover:border-primary-300
              bg-white hover:bg-primary-50 text-gray-700 hover:text-primary-700 font-semibold rounded-xl
              transition-all duration-200 focus:ring-4 focus:ring-primary-100 outline-none">
        <span>Daftar Sekarang</span>
        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
        </svg>
    </a>

    <!-- Demo Accounts -->
    <div class="mt-8 p-4 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">Akun Demo</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                    <div class="bg-white/70 backdrop-blur rounded-lg p-3 border border-blue-100">
                        <p class="font-semibold text-gray-700 mb-1">Admin</p>
                        <p class="text-gray-600 break-all">admin@mance.go.id</p>
                        <p class="text-gray-600">password123</p>
                    </div>
                    <div class="bg-white/70 backdrop-blur rounded-lg p-3 border border-blue-100">
                        <p class="font-semibold text-gray-700 mb-1">User</p>
                        <p class="text-gray-600 break-all">budi.santoso@email.com</p>
                        <p class="text-gray-600">password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Onboarding Link -->
    <div class="text-center">
        <a href="{{ route('onboarding') }}"
            class="inline-flex items-center gap-2 text-sm text-primary-600 hover:text-primary-700 font-medium group">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
            <span class="group-hover:underline">Pelajari fitur aplikasi</span>
        </a>
    </div>
</div>
@endsection
