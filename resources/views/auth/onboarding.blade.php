@extends('layouts.guest')

@section('title', 'Onboarding')

@section('header')
<div class="mt-2 text-center">
    <div class="inline-flex items-center px-3 py-1 rounded-full bg-primary-100 text-primary-800 text-xs font-medium">
        Selamat Datang di MANCE
    </div>
    <h2 class="mt-3 text-2xl sm:text-3xl font-extrabold tracking-tight">
        <span class="bg-gradient-to-r from-primary-600 to-accent-600 bg-clip-text text-transparent">
            Mulai Jelajahi Fitur Utama
        </span>
    </h2>
</div>
@endsection

@section('content')
<div x-data="onboarding()" x-init="init()" class="select-none">
    <!-- Slides -->
    <div class="relative overflow-hidden">
        <template x-for="(item, i) in slides" :key="i">
            <div x-show="index === i" x-transition.opacity x-transition.scale.origin.center class="space-y-5">
                <!-- Illustration -->
                <div class="flex justify-center">
                    <div class="relative">
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-primary-400 to-accent-400 rounded-2xl blur-2xl opacity-30">
                        </div>
                        <div
                            class="relative h-28 w-28 sm:h-32 sm:w-32 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-100 shadow-2xl flex items-center justify-center">
                            <svg x-html="item.icon" class="h-14 w-14 sm:h-16 sm:w-16 text-primary-600"></svg>
                        </div>
                    </div>
                </div>

                <div class="text-center space-y-2">
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900" x-text="item.title"></h3>
                    <p class="text-sm text-gray-600" x-text="item.desc"></p>
                </div>
            </div>
        </template>
    </div>

    <!-- Progress Dots -->
    <div class="mt-6 flex items-center justify-center space-x-2">
        <template x-for="(item, i) in slides" :key="'dot-'+i">
            <button @click="go(i)" :aria-current="index === i ? 'step' : false"
                class="h-2.5 rounded-full transition-all"
                :class="index === i ? 'w-6 bg-gradient-to-r from-primary-500 to-accent-500' : 'w-2.5 bg-gray-300'"></button>
        </template>
    </div>

    <!-- Actions -->
    <div class="mt-6 flex items-center justify-between">
        <button type="button" @click="skip()" class="text-xs sm:text-sm font-medium text-gray-500 hover:text-gray-700">
            Lewati
        </button>

        <div class="flex items-center space-x-2">
            <button type="button" @click="prev()" x-show="index > 0" x-transition
                class="px-3 py-2 text-xs sm:text-sm rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700">
                Kembali
            </button>

            <button type="button" x-show="!isLast()" @click="next()" x-transition
                class="inline-flex items-center px-4 py-2 rounded-lg text-white text-xs sm:text-sm font-semibold bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700">
                Lanjut
                <svg class="ml-1.5 h-4 w-4 -mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h13M12 5l7 7-7 7">
                    </path>
                </svg>
            </button>

            <a x-show="isLast()" x-transition href="{{ route('login') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg text-white text-xs sm:text-sm font-semibold bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700">
                Mulai Sekarang
                <svg class="ml-1.5 h-4 w-4 -mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h13M12 5l7 7-7 7">
                    </path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Quick Access -->
    <div class="mt-8 grid grid-cols-2 gap-3">
        <a href="{{ route('login') }}"
            class="text-center text-xs sm:text-sm font-medium text-primary-600 hover:text-primary-700">
            Masuk
        </a>
        <a href="{{ route('register') }}"
            class="text-center text-xs sm:text-sm font-medium text-primary-600 hover:text-primary-700">
            Daftar
        </a>
    </div>
</div>

@push('scripts')
<script>
    function onboarding() {
    return {
        index: 0,
        slides: [
            {
                title: 'Semua Layanan dalam Satu Tempat',
                desc: 'Akses layanan publik Kabupaten Muara Enim secara cepat dan terintegrasi.',
                icon: `<svg viewBox="0 0 24 24" stroke="currentColor" fill="none">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18"/>
                       </svg>`
            },
            {
                title: 'Pengaduan Cepat & Transparan',
                desc: 'Sampaikan aspirasi dan pantau progres penanganan secara real-time.',
                icon: `<svg viewBox="0 0 24 24" stroke="currentColor" fill="none">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 21l1.8-4A7.8 7.8 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                       </svg>`
            },
            {
                title: 'Informasi & Berita Terkini',
                desc: 'Ikuti update penting dan pengumuman resmi dari pemerintah daerah.',
                icon: `<svg viewBox="0 0 24 24" stroke="currentColor" fill="none">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v1m2 12a2 2 0 01-2-2V9"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12h6M7 16h6"/>
                       </svg>`
            }
        ],
        init(){ this.index = 0; },
        isLast(){ return this.index === this.slides.length - 1; },
        next(){ if(!this.isLast()) this.index++; },
        prev(){ if(this.index > 0) this.index--; },
        go(i){ this.index = i; },
        skip(){ window.location.href = "{{ route('login') }}"; }
    }
}
</script>
@endpush
@endsection
