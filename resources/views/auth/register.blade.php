@extends('layouts.guest')

@section('title', 'Registrasi')

@section('header')
<div class="text-center mb-6">
    <div
        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-accent-500/10 to-pink-500/10 border border-accent-200/50 backdrop-blur-sm mb-4">
        <svg class="w-4 h-4 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
        </svg>
        <span class="text-sm font-semibold text-accent-700">Bergabung Dengan Kami</span>
    </div>

    <h2 class="text-3xl font-black text-gray-900 mb-2">
        Daftar Akun MANCE
    </h2>
    <p class="text-gray-600">
        Lengkapi data untuk mulai menggunakan layanan
    </p>
</div>
@endsection

@section('content')
<div x-data="{
        step: 1,
        totalSteps: 2,
        showPassword: false,
        showConfirm: false,
        formData: {
            name: '{{ old('name') }}',
            email: '{{ old('email') }}',
            phone: '{{ old('phone') }}',
            category: '{{ old('category') }}',
            password: '',
            password_confirmation: '',
            terms: false
        },
        strength: 0,
        calcStrength() {
            let p = this.formData.password;
            let s = 0;
            if (p.length >= 8) s++;
            if (/[A-Z]/.test(p) && /[a-z]/.test(p)) s++;
            if (/[0-9]/.test(p)) s++;
            if (/[\W_]/.test(p)) s++;
            this.strength = s;
        },
        canProceedStep1() {
            return this.formData.name.length > 2 &&
                   this.formData.email.includes('@') &&
                   this.formData.phone.length >= 10 &&
                   this.formData.category !== '';
        },
        canSubmit() {
            return this.formData.password.length >= 8 &&
                   this.formData.password === this.formData.password_confirmation &&
                   this.formData.terms;
        },
        nextStep() {
            if (this.step < this.totalSteps && this.canProceedStep1()) {
                this.step++;
            }
        },
        prevStep() {
            if (this.step > 1) this.step--;
        }
    }" class="space-y-6">

    <!-- Step Indicator -->
    <div class="flex items-center justify-between mb-8">
        <template x-for="i in totalSteps" :key="i">
            <div class="flex-1" :class="{ 'ml-4': i > 1 }">
                <div class="relative flex items-center">
                    <!-- Line -->
                    <div x-show="i > 1"
                        class="absolute right-1/2 top-1/2 -translate-y-1/2 w-full h-0.5 transition-all duration-300"
                        :class="step >= i ? 'bg-gradient-to-r from-primary-500 to-accent-500' : 'bg-gray-200'"></div>

                    <!-- Circle -->
                    <div class="relative z-10 w-full flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold transition-all duration-300 shadow-lg"
                            :class="step >= i ? 'bg-gradient-to-r from-primary-500 to-accent-500 text-white scale-110' : 'bg-gray-200 text-gray-500'">
                            <template x-if="step > i">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </template>
                            <template x-if="step <= i">
                                <span x-text="i"></span>
                            </template>
                        </div>
                        <span class="mt-2 text-xs font-medium" :class="step >= i ? 'text-primary-600' : 'text-gray-400'"
                            x-text="i === 1 ? 'Data Diri' : 'Keamanan'"></span>
                    </div>
                </div>
            </div>
        </template>
    </div>

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

    <form action="{{ route('register') }}" method="POST" class="space-y-5">
        @csrf

        <!-- Step 1: Personal Info -->
        <div x-show="step === 1" x-transition class="space-y-5">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input type="text" id="name" name="name" required x-model="formData.name" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                               focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                               transition-all duration-200 outline-none @error('name') border-red-300 @enderror"
                        placeholder="Masukkan nama lengkap">
                </div>
                @error('name')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input type="email" id="email" name="email" required x-model="formData.email" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                               focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                               transition-all duration-200 outline-none @error('email') border-red-300 @enderror"
                        placeholder="nama@email.com">
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

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <input type="tel" id="phone" name="phone" required x-model="formData.phone" class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                               focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                               transition-all duration-200 outline-none @error('phone') border-red-300 @enderror"
                        placeholder="08xxxxxxxxxx">
                </div>
                @error('phone')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Kategori Masyarakat</label>
                <select id="category" name="category" required x-model="formData.category" class="w-full px-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900
                           focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                           transition-all duration-200 outline-none @error('category') border-red-300 @enderror">
                    <option value="">Pilih kategori</option>
                    <option value="pelajar">Pelajar</option>
                    <option value="pegawai">Pegawai</option>
                    <option value="pencari_kerja">Pencari Kerja</option>
                    <option value="pengusaha">Pengusaha</option>
                </select>
                @error('category')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <button type="button" @click="nextStep()" :disabled="!canProceedStep1()" class="group relative w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700
                       text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40
                       transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed
                       focus:ring-4 focus:ring-primary-300 outline-none">
                <span class="flex items-center justify-center gap-2">
                    <span>Lanjutkan</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </span>
            </button>
        </div>

        <!-- Step 2: Security -->
        <div x-show="step === 2" x-transition class="space-y-5">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                        x-model="formData.password" @input="calcStrength()" class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                               focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                               transition-all duration-200 outline-none @error('password') border-red-300 @enderror"
                        placeholder="••••••••">
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
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

                <!-- Strength Meter -->
                <div class="mt-3">
                    <div class="h-2 w-full rounded-full bg-gray-200 overflow-hidden">
                        <div class="h-full transition-all duration-300" :class="{
                                'w-0 bg-transparent': strength === 0,
                                'w-1/4 bg-red-500': strength === 1,
                                'w-1/2 bg-orange-500': strength === 2,
                                'w-3/4 bg-yellow-500': strength === 3,
                                'w-full bg-green-500': strength === 4
                             }"></div>
                    </div>
                    <p class="mt-2 text-xs text-gray-500">
                        Minimal 8 karakter. Kombinasi huruf besar, kecil, angka, dan simbol lebih aman.
                    </p>
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

            <!-- Password Confirmation -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi
                    Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation"
                        name="password_confirmation" required x-model="formData.password_confirmation" class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-400
                               focus:bg-white focus:border-primary-500 focus:ring-4 focus:ring-primary-100
                               transition-all duration-200 outline-none" placeholder="••••••••">
                    <button type="button" @click="showConfirm = !showConfirm"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                        <svg x-show="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="showConfirm" x-cloak class="w-5 h-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.98 9.98 0 01-2.342 3.944m4.217 4.051A9.935 9.935 0 0112 21c4.478 0 8.268-2.943 9.543-7a10.025 10.025 0 00-3.226-5.166M9.88 9.88a3 3 0 104.24 4.24M9.88 9.88L6.172 6.172M9.88 9.88l4.24 4.24m0 0l3.708 3.708m-7.948-3.708l-3.708-3.708" />
                        </svg>
                    </button>
                </div>

                <!-- Match indicator -->
                <div x-show="formData.password_confirmation.length > 0" x-cloak
                    class="mt-2 flex items-center gap-2 text-sm"
                    :class="formData.password === formData.password_confirmation ? 'text-green-600' : 'text-red-600'">
                    <svg x-show="formData.password === formData.password_confirmation" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg x-show="formData.password !== formData.password_confirmation" class="w-4 h-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span
                        x-text="formData.password === formData.password_confirmation ? 'Password cocok' : 'Password tidak cocok'"></span>
                </div>
            </div>

            <!-- Terms -->
            <div class="pt-2">
                <label class="flex items-start gap-3 cursor-pointer group">
                    <input type="checkbox" name="terms" required x-model="formData.terms"
                        class="mt-1 w-5 h-5 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 focus:ring-2 transition-all">
                    <span class="text-sm text-gray-700 group-hover:text-gray-900">
                        Saya setuju dengan
                        <a href="#" class="text-primary-600 hover:text-primary-700 font-medium underline">Syarat &
                            Ketentuan</a>
                        dan
                        <a href="#" class="text-primary-600 hover:text-primary-700 font-medium underline">Kebijakan
                            Privasi</a>
                    </span>
                </label>
                @error('terms')
                <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex gap-3 pt-2">
                <button type="button" @click="prevStep()"
                    class="flex-1 py-3.5 px-6 border-2 border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50
                           text-gray-700 font-semibold rounded-xl transition-all duration-200 focus:ring-4 focus:ring-gray-200 outline-none">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span>Kembali</span>
                    </span>
                </button>

                <button type="submit" :disabled="!canSubmit()" class="group relative flex-[2] py-3.5 px-6 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700
                           text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40
                           transform hover:-translate-y-0.5 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed
                           focus:ring-4 focus:ring-primary-300 outline-none">
                    <span class="flex items-center justify-center gap-2">
                        <span>Daftar Sekarang</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                </button>
            </div>
        </div>
    </form>

    <!-- Login Link -->
    <div class="text-center pt-4">
        <p class="text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}"
                class="font-semibold text-primary-600 hover:text-primary-700 hover:underline">
                Masuk di sini
            </a>
        </p>
    </div>
</div>
@endsection
