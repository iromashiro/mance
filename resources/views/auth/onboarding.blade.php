@extends('layouts.guest')

@section('title', 'Selamat Datang')

@section('header')
<div class="text-center mb-6">
    <div
        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-indigo-500/10 to-purple-500/10 border border-indigo-200/50 backdrop-blur-sm mb-4 animate-bounce">
        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
        </svg>
        <span class="text-sm font-semibold text-indigo-700">Selamat Datang</span>
    </div>

    <h2 class="text-3xl font-black text-gray-900 mb-2">
        Jelajahi MANCE
    </h2>
    <p class="text-gray-600">
        Kenali fitur-fitur unggulan kami
    </p>
</div>
@endsection

@section('content')
<div x-data="{
        currentSlide: 0,
        totalSlides: 4,
        touchStartX: 0,
        touchEndX: 0,
        isAnimating: false,
        autoplayInterval: null,
        autoplayDelay: 5000,

        slides: [
            {
                icon: `<svg class='w-20 h-20' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'/>
                </svg>`,
                gradient: 'from-blue-500 to-indigo-600',
                title: 'Dashboard Terpadu',
                desc: 'Akses semua layanan pemerintahan dalam satu platform yang mudah dan cepat'
            },
            {
                icon: `<svg class='w-20 h-20' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'/>
                </svg>`,
                gradient: 'from-amber-500 to-orange-600',
                title: 'Pengaduan Online',
                desc: 'Sampaikan aspirasi dan keluhan dengan mudah, pantau progres secara real-time'
            },
            {
                icon: `<svg class='w-20 h-20' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15'/>
                </svg>`,
                gradient: 'from-green-500 to-emerald-600',
                title: 'Info & Berita Terkini',
                desc: 'Dapatkan update penting dan pengumuman resmi dari pemerintah daerah'
            },
            {
                icon: `<svg class='w-20 h-20' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                    <path stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/>
                </svg>`,
                gradient: 'from-purple-500 to-pink-600',
                title: 'Aman & Terpercaya',
                desc: 'Data Anda dilindungi dengan enkripsi tingkat tinggi dan sistem keamanan terbaik'
            }
        ],

        init() {
            this.startAutoplay();
        },

        startAutoplay() {
            this.autoplayInterval = setInterval(() => {
                this.next();
            }, this.autoplayDelay);
        },

        stopAutoplay() {
            if (this.autoplayInterval) {
                clearInterval(this.autoplayInterval);
                this.autoplayInterval = null;
            }
        },

        resetAutoplay() {
            this.stopAutoplay();
            this.startAutoplay();
        },

        next() {
            if (this.isAnimating) return;
            this.isAnimating = true;

            if (this.currentSlide < this.totalSlides - 1) {
                this.currentSlide++;
            } else {
                this.currentSlide = 0;
            }

            setTimeout(() => {
                this.isAnimating = false;
            }, 500);

            this.resetAutoplay();
        },

        prev() {
            if (this.isAnimating) return;
            this.isAnimating = true;

            if (this.currentSlide > 0) {
                this.currentSlide--;
            } else {
                this.currentSlide = this.totalSlides - 1;
            }

            setTimeout(() => {
                this.isAnimating = false;
            }, 500);

            this.resetAutoplay();
        },

        goTo(index) {
            if (this.isAnimating || index === this.currentSlide) return;
            this.isAnimating = true;
            this.currentSlide = index;

            setTimeout(() => {
                this.isAnimating = false;
            }, 500);

            this.resetAutoplay();
        },

        handleTouchStart(e) {
            this.touchStartX = e.touches[0].clientX;
            this.stopAutoplay();
        },

        handleTouchMove(e) {
            this.touchEndX = e.touches[0].clientX;
        },

        handleTouchEnd() {
            const diff = this.touchStartX - this.touchEndX;
            const threshold = 50;

            if (Math.abs(diff) > threshold) {
                if (diff > 0) {
                    this.next();
                } else {
                    this.prev();
                }
            } else {
                this.startAutoplay();
            }
        }
    }" class="relative select-none" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()"
    @touchstart="handleTouchStart($event)" @touchmove="handleTouchMove($event)" @touchend="handleTouchEnd()">

    <!-- Slides Container -->
    <div class="relative h-96 overflow-hidden rounded-2xl mb-8">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="currentSlide === index" x-transition:enter="transition transform duration-500"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition transform duration-500" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center">

                <!-- Icon with animated background -->
                <div class="relative mb-8 group">
                    <div class="absolute inset-0 rounded-full blur-2xl opacity-30 group-hover:opacity-50 transition-opacity duration-300"
                        :class="`bg-gradient-to-r ${slide.gradient}`"></div>
                    <div class="relative w-32 h-32 rounded-3xl flex items-center justify-center shadow-2xl backdrop-blur-sm border border-white/50 bg-white/80"
                        :class="`bg-gradient-to-br ${slide.gradient} text-white`" x-html="slide.icon">
                    </div>
                </div>

                <!-- Content -->
                <h3 class="text-2xl font-black text-gray-900 mb-4" x-text="slide.title"></h3>
                <p class="text-gray-600 max-w-sm mx-auto leading-relaxed" x-text="slide.desc"></p>
            </div>
        </template>
    </div>

    <!-- Dots Navigation -->
    <div class="flex items-center justify-center gap-2 mb-8">
        <template x-for="index in totalSlides" :key="index">
            <button @click="goTo(index - 1)" class="relative group focus:outline-none">
                <!-- Progress ring for current slide -->
                <template x-if="currentSlide === index - 1">
                    <svg class="absolute inset-0 w-full h-full -rotate-90" viewBox="0 0 36 36">
                        <circle cx="18" cy="18" r="16" fill="none" stroke="currentColor" class="text-primary-200"
                            stroke-width="2" />
                        <circle cx="18" cy="18" r="16" fill="none" stroke="currentColor" class="text-primary-600"
                            stroke-width="2" stroke-dasharray="100"
                            :stroke-dashoffset="100 - (100 * ((Date.now() % autoplayDelay) / autoplayDelay))"
                            style="transition: stroke-dashoffset 100ms linear" />
                    </svg>
                </template>

                <div class="w-3 h-3 rounded-full transition-all duration-300"
                    :class="currentSlide === index - 1 ? 'bg-gradient-to-r from-primary-500 to-accent-500 scale-125' : 'bg-gray-300 hover:bg-gray-400'">
                </div>
            </button>
        </template>
    </div>

    <!-- Navigation Arrows (Desktop) -->
    <div class="hidden md:block">
        <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/80 backdrop-blur-sm shadow-lg
                   hover:bg-white hover:scale-110 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300
                   disabled:opacity-50 disabled:cursor-not-allowed" :disabled="isAnimating">
            <svg class="w-6 h-6 mx-auto text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>

        <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/80 backdrop-blur-sm shadow-lg
                   hover:bg-white hover:scale-110 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-primary-300
                   disabled:opacity-50 disabled:cursor-not-allowed" :disabled="isAnimating">
            <svg class="w-6 h-6 mx-auto text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('login') }}"
            class="group flex-1 py-4 px-6 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700
                  text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl hover:shadow-primary-500/40
                  transform hover:-translate-y-0.5 transition-all duration-200 text-center focus:ring-4 focus:ring-primary-300 outline-none">
            <span class="flex items-center justify-center gap-2">
                <span>Masuk</span>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
            </span>
        </a>

        <a href="{{ route('register') }}" class="group flex-1 py-4 px-6 border-2 border-gray-300 hover:border-primary-300 bg-white hover:bg-primary-50
                  text-gray-700 hover:text-primary-700 font-semibold rounded-xl transition-all duration-200 text-center
                  focus:ring-4 focus:ring-primary-100 outline-none">
            <span class="flex items-center justify-center gap-2">
                <span>Daftar</span>
                <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </span>
        </a>
    </div>

    <!-- Skip Link -->
    <div class="text-center mt-6">
        <button @click="window.location.href = '{{ route('login') }}'"
            class="text-sm text-gray-500 hover:text-gray-700 font-medium hover:underline transition-colors">
            Lewati panduan
        </button>
    </div>

    <!-- Keyboard navigation hint -->
    <div class="hidden md:flex items-center justify-center gap-4 mt-8 text-xs text-gray-400">
        <div class="flex items-center gap-1">
            <kbd class="px-2 py-1 bg-gray-100 rounded border border-gray-200 text-gray-600">←</kbd>
            <span>Sebelumnya</span>
        </div>
        <div class="flex items-center gap-1">
            <kbd class="px-2 py-1 bg-gray-100 rounded border border-gray-200 text-gray-600">→</kbd>
            <span>Selanjutnya</span>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
        Alpine.store('onboarding')?.prev();
    } else if (e.key === 'ArrowRight') {
        Alpine.store('onboarding')?.next();
    }
});
</script>
@endpush
@endsection
