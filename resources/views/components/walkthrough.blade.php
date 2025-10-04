{{-- Walkthrough Overlay - muncul sekali per device setelah login --}}
<div id="walkthrough" x-data="walkthrough()" x-init="init()" x-show="open" x-cloak
    class="fixed inset-0 z-[10001] flex items-end sm:items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm">

    <!-- Panel -->
    <div x-transition.scale.origin.center
        class="w-full max-w-md rounded-2xl bg-white shadow-2xl ring-1 ring-black/5 overflow-hidden">

        <!-- Header gradient -->
        <div class="bg-gradient-to-r from-primary-600 to-accent-600 px-5 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span
                        class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/15 ring-1 ring-white/20">
                        <svg class="h-5 w-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" />
                        </svg>
                    </span>
                    <p class="text-white font-semibold">Panduan Cepat</p>
                </div>
                <button type="button" @click="skip()" class="text-white/80 hover:text-white text-sm">Lewati</button>
            </div>
        </div>

        <!-- Content -->
        <div class="px-5 py-5">
            <!-- Slides -->
            <template x-if="step === 0">
                <div x-transition.opacity class="space-y-2">
                    <div class="flex items-center justify-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-primary-400/20 rounded-2xl blur-2xl"></div>
                            <div
                                class="relative h-20 w-20 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-100 shadow-2xl flex items-center justify-center">
                                <svg class="h-10 w-10 text-primary-600" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2 7-7 7 7 2 2v7a2 2 0 01-2 2h-3m-6 0a2 2 0 01-2-2v-4h6v4a2 2 0 01-2 2h-2" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center text-lg font-bold text-gray-900">Dashboard & Navigasi</h3>
                    <p class="text-center text-sm text-gray-600">
                        Akses cepat ke Dashboard, Layanan, Pengaduan, Berita, dan Profil melalui navigasi bawah.
                    </p>
                </div>
            </template>

            <template x-if="step === 1">
                <div x-transition.opacity class="space-y-2">
                    <div class="flex items-center justify-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-accent-400/20 rounded-2xl blur-2xl"></div>
                            <div
                                class="relative h-20 w-20 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-100 shadow-2xl flex items-center justify-center">
                                <svg class="h-10 w-10 text-accent-600" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v8m4-4H8" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center text-lg font-bold text-gray-900">Buat Pengaduan</h3>
                    <p class="text-center text-sm text-gray-600">
                        Laporkan keluhan Anda dengan tombol Lapor di tengah navigasi bawah. Pantau progresnya secara
                        real-time.
                    </p>
                </div>
            </template>

            <template x-if="step === 2">
                <div x-transition.opacity class="space-y-2">
                    <div class="flex items-center justify-center">
                        <div class="relative">
                            <div class="absolute inset-0 bg-emerald-400/20 rounded-2xl blur-2xl"></div>
                            <div
                                class="relative h-20 w-20 rounded-2xl bg-gradient-to-br from-white to-gray-50 border border-gray-100 shadow-2xl flex items-center justify-center">
                                <svg class="h-10 w-10 text-emerald-600" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2 2 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .53-.21 1.04-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    <h3 class="text-center text-lg font-bold text-gray-900">Notifikasi & Profil</h3>
                    <p class="text-center text-sm text-gray-600">
                        Lihat notifikasi terbaru dan kelola profil Anda dari pojok kanan atas.
                    </p>
                </div>
            </template>

            <!-- Progress -->
            <div class="mt-5 flex items-center justify-center space-x-2">
                <template x-for="i in steps" :key="'dot-'+i">
                    <span class="h-2.5 rounded-full transition-all"
                        :class="(i-1) === step ? 'w-6 bg-gradient-to-r from-primary-500 to-accent-500' : 'w-2.5 bg-gray-300'"></span>
                </template>
            </div>

            <!-- Actions -->
            <div class="mt-5 flex items-center justify-between">
                <button type="button"
                    class="px-3 py-2 text-xs sm:text-sm rounded-lg border border-gray-200 hover:bg-gray-50 text-gray-700"
                    x-show="step > 0" x-transition @click="prev()">Kembali</button>

                <div class="ml-auto flex items-center space-x-2">
                    <button type="button"
                        class="px-4 py-2 text-xs sm:text-sm rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700"
                        @click="skip()">Lewati</button>

                    <button type="button" x-show="!isLast()" x-transition
                        class="inline-flex items-center px-4 py-2 rounded-lg text-white text-xs sm:text-sm font-semibold bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700"
                        @click="next()">
                        Lanjut
                        <svg class="ml-1.5 h-4 w-4 -mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h13M12 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <button type="button" x-show="isLast()" x-transition
                        class="inline-flex items-center px-4 py-2 rounded-lg text-white text-xs sm:text-sm font-semibold bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700"
                        @click="finish()">
                        Mulai
                        <svg class="ml-1.5 h-4 w-4 -mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 12h13M12 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function walkthrough() {
    return {
        open: false,
        step: 0,
        steps: 3,
        init() {
            try {
                const seen = localStorage.getItem('mance_walkthrough_v1_seen');
                this.open = !seen;
            } catch (e) {
                this.open = true;
            }
        },
        isLast() { return this.step >= this.steps - 1; },
        next() { if (!this.isLast()) this.step++; else this.finish(); },
        prev() { if (this.step > 0) this.step--; },
        skip() { this.finish(); },
        finish() {
            try { localStorage.setItem('mance_walkthrough_v1_seen', '1'); } catch (e) {}
            this.open = false;
        }
    }
}
</script>
@endpush
