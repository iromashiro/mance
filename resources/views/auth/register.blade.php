@extends('layouts.guest')

@section('title', 'Registrasi')

@section('header')
<div class="mt-6 text-center">
    <div class="inline-flex items-center px-3 py-1 rounded-full bg-primary-100 text-primary-800 text-xs font-medium">
        Ayo Bergabung
    </div>
    <h2 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight">
        <span class="bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
            Buat Akun MANCE
        </span>
    </h2>
    <p class="mt-2 text-sm text-gray-600">
        Satu akun untuk semua layanan Smart City Muara Enim
    </p>
    <div class="mt-4 text-xs sm:text-sm">
        <span class="text-gray-500">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="font-semibold text-primary-600 hover:text-primary-700">Masuk</a>
    </div>
</div>
@endsection

@section('content')
<div x-data="{
        showPassword:false,
        showConfirm:false,
        pw:'',
        strength:0,
        calc(){
            let p=this.pw||'';
            let s=0;
            if(p.length >= 8) s++;
            if(/[A-Z]/.test(p) && /[a-z]/.test(p)) s++;
            if(/[0-9\W]/.test(p)) s++;
            this.strength=s;
        }
    }" class="space-y-6">

    {{-- Error summary --}}
    @if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-3 text-red-700 text-sm">
        {{ $errors->first() }}
    </div>
    @endif

    <form class="space-y-5" action="{{ route('register') }}" method="POST">
        @csrf

        {{-- Name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
            <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </span>
                <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                    class="block w-full pl-10 pr-3 py-2.5 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/60 focus:border-primary-500 @error('name') border-red-300 focus:ring-red-300 @enderror">
            </div>
            @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 12H8m8 0a8 8 0 11-16 0 8 8 0 0116 0zM12 12v.01" />
                    </svg>
                </span>
                <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                    class="block w-full pl-10 pr-3 py-2.5 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/60 focus:border-primary-500 @error('email') border-red-300 focus:ring-red-300 @enderror">
            </div>
            @error('email')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
            <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5h2l3 7-1.5 2.5A2 2 0 008 17h8m0 0a2 2 0 002-2V7a2 2 0 00-2-2H6" />
                    </svg>
                </span>
                <input id="phone" name="phone" type="tel" autocomplete="tel" required placeholder="08xxxxxxxxxx"
                    value="{{ old('phone') }}"
                    class="block w-full pl-10 pr-3 py-2.5 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/60 focus:border-primary-500 @error('phone') border-red-300 focus:ring-red-300 @enderror">
            </div>
            @error('phone')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Category --}}
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Kategori Masyarakat</label>
            <div class="mt-1 relative">
                <select id="category" name="category" required
                    class="block w-full pl-3 pr-10 py-2.5 border rounded-lg shadow-sm bg-white focus:outline-none focus:ring-2 focus:ring-primary-500/60 focus:border-primary-500 @error('category') border-red-300 focus:ring-red-300 @enderror">
                    <option value="">Pilih kategori</option>
                    <option value="pelajar" {{ old('category') == 'pelajar' ? 'selected' : '' }}>Pelajar</option>
                    <option value="pegawai" {{ old('category') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                    <option value="pencari_kerja" {{ old('category') == 'pencari_kerja' ? 'selected' : '' }}>Pencari
                        Kerja</option>
                    <option value="pengusaha" {{ old('category') == 'pengusaha' ? 'selected' : '' }}>Pengusaha</option>
                </select>
            </div>
            @error('category')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.4 15a8 8 0 10-14.8 0" />
                    </svg>
                </span>
                <input :type="showPassword ? 'text' : 'password'" id="password" name="password"
                    autocomplete="new-password" required x-model="pw" @input="calc()"
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
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.98 9.98 0 01-2.342 3.944" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.88 9.88a3 3 0 104.24 4.24M6.1 6.1l11.8 11.8" />
                    </svg>
                </button>
            </div>
            @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            {{-- Strength meter --}}
            <div class="mt-2">
                <div class="h-1.5 w-full rounded-full bg-gray-200 overflow-hidden">
                    <div class="h-full transition-all" :class="{
                            'w-0 bg-transparent': strength === 0,
                            'w-1/3 bg-red-500': strength === 1,
                            'w-2/3 bg-yellow-500': strength === 2,
                            'w-full bg-green-500': strength >= 3
                         }"></div>
                </div>
                <p class="mt-1 text-xs text-gray-500">
                    Minimal 8 karakter. Gunakan kombinasi huruf besar, kecil, dan angka/simbol.
                </p>
            </div>
        </div>

        {{-- Confirm password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                Password</label>
            <div class="mt-1 relative">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.4 15a8 8 0 10-14.8 0" />
                    </svg>
                </span>
                <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation"
                    autocomplete="new-password" required
                    class="block w-full pl-10 pr-10 py-2.5 border rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500/60 focus:border-primary-500">
                <button type="button"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600"
                    @click="showConfirm = !showConfirm" aria-label="Toggle confirm password">
                    <svg x-show="!showConfirm" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirm" x-cloak class="h-5 w-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.98 9.98 0 01-2.342 3.944" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.88 9.88a3 3 0 104.24 4.24M6.1 6.1l11.8 11.8" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Terms --}}
        <div>
            <label class="flex items-start space-x-2 cursor-pointer">
                <input id="terms" name="terms" type="checkbox" required
                    class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <span class="text-sm text-gray-700">
                    Saya setuju dengan
                    <a href="#" class="text-primary-600 hover:text-primary-500">Syarat & Ketentuan</a>
                    dan
                    <a href="#" class="text-primary-600 hover:text-primary-500">Kebijakan Privasi</a>.
                </span>
            </label>
            @error('terms')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit"
                class="w-full inline-flex items-center justify-center py-2.5 px-4 rounded-lg text-sm font-semibold text-white shadow-sm
                           bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                Daftar
                <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </form>
</div>
@endsection
