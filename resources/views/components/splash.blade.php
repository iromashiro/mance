{{-- Splash Screen - tampil sekali per device --}}
<style>
    html[data-splash-seen="1"] #app-splash {
        display: none !important;
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div id="app-splash" x-data="{ open: !localStorage.getItem('mance_splash_v1_seen') }" x-show="open" x-init="
        setTimeout(() => {
            try { localStorage.setItem('mance_splash_v1_seen', '1') } catch(e) {}
            open = false
        }, 1400)
     " x-transition.opacity.duration.400ms
    class="fixed inset-0 z-[10000] flex items-center justify-center bg-gradient-to-br from-primary-600 via-primary-700 to-accent-600 text-white select-none">

    <div class="text-center">
        <div class="flex justify-center mb-6">
            <div
                class="h-20 w-20 rounded-2xl bg-white/10 backdrop-blur-lg ring-1 ring-white/20 flex items-center justify-center shadow-glow animate-[fade-in-up_.4s_ease-out]">
                <img src='{{ URL::asset('mekab-logo.webp') }}' alt="MANCE" class="h-12 w-12 drop-shadow" />
            </div>
        </div>
        <h1 class="text-3xl font-extrabold tracking-tight animate-[fade-in-up_.45s_.05s_ease-out_both]">
            MANCE
        </h1>
        <p class="mt-1 text-white/80 animate-[fade-in-up_.45s_.1s_ease-out_both]">
            Muara Enim Smart City
        </p>
    </div>
</div>
