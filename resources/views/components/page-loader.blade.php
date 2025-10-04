{{-- Page Loader Overlay (Logo 3D) --}}
<style>
    /* Scoped to #page-loader to avoid global bleed */
    #page-loader .scene {
        width: 112px;
        height: 112px;
        perspective: 1000px;
    }

    #page-loader .card {
        position: relative;
        width: 112px;
        height: 112px;
        transform-style: preserve-3d;
        animation: spinY 2.4s linear infinite;
        filter: drop-shadow(0 16px 32px rgba(121, 80, 242, .35));
        border-radius: 20px;
    }

    #page-loader .face {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        backface-visibility: hidden;
        border-radius: 20px;
        background: linear-gradient(135deg, rgba(255, 255, 255, .15), rgba(255, 255, 255, .06));
        border: 1px solid rgba(255, 255, 255, .25);
    }

    #page-loader .face.back {
        transform: rotateY(180deg);
    }

    #page-loader .face img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        filter: drop-shadow(0 2px 2px rgba(0, 0, 0, .25));
    }

    #page-loader .shadow {
        width: 120px;
        height: 22px;
        margin: 16px auto 0;
        background: radial-gradient(closest-side, rgba(0, 0, 0, .35), rgba(0, 0, 0, 0));
        filter: blur(2px);
        animation: shadow-pulse 2.4s ease-in-out infinite;
    }

    @keyframes spinY {
        0% {
            transform: rotateX(-18deg) rotateY(0);
        }

        50% {
            transform: rotateX(-18deg) rotateY(180deg);
        }

        100% {
            transform: rotateX(-18deg) rotateY(360deg);
        }
    }

    @keyframes shadow-pulse {

        0%,
        100% {
            transform: scale(0.95);
            opacity: .55;
        }

        50% {
            transform: scale(1.05);
            opacity: .85;
        }
    }
</style>

<div id="page-loader" role="status" aria-live="polite" aria-busy="true"
    class="hidden fixed inset-0 z-[9999] items-center justify-center bg-gray-900/60 backdrop-blur-md select-none">
    <div class="flex flex-col items-center space-y-6 pointer-events-none">
        <div class="relative pointer-events-none">
            <div class="scene mx-auto">
                <div class="card">
                    <div class="face front">
                        <img src="{{ URL::asset('mekab-logo.webp') }}" alt="Memuat - Logo Muara Enim">
                    </div>
                    <div class="face back">
                        <img src="{{ URL::asset('mekab-logo.webp') }}" alt="">
                    </div>
                </div>
            </div>
            <div class="shadow mx-auto"></div>
        </div>
        <div class="text-white/90 text-xs tracking-[.35em] uppercase">Memuat...</div>
    </div>
</div>
