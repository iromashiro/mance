@extends('layouts.app')

@section('title', 'Panggilan Aruna AI')

@push('styles')
<style>
    /* Hide bottom nav for fullscreen call experience */
    nav.lg\:hidden {
        display: none !important;
    }

    @keyframes pulse-ring {
        0% {
            transform: scale(0.95);
            opacity: 1;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.5;
        }

        100% {
            transform: scale(0.95);
            opacity: 1;
        }
    }

    .pulse-ring {
        animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes wave {

        0%,
        100% {
            height: 20%;
        }

        50% {
            height: 80%;
        }
    }

    .audio-wave {
        animation: wave 1.2s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<div x-data="phoneCall()" x-init="autoStartCall()"
    class="fixed inset-0 bg-gradient-to-br from-primary-600 via-purple-600 to-accent-600 z-50">

    <!-- Main Call UI -->
    <div class="h-full flex flex-col items-center justify-center px-6 pb-20">
        <!-- Call Duration (above status badge) -->
        <div class="mb-4" x-show="callDuration > 0">
            <div class="text-white text-2xl font-semibold tracking-wider" x-text="formatDuration(callDuration)">
                00:00
            </div>
        </div>

        <!-- Status Badge -->
        <div class="mb-8">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/20 backdrop-blur-md border border-white/30">
                <div class="relative">
                    <div class="w-2 h-2 rounded-full bg-white" x-bind:class="{ 'pulse-ring': isConnected }"></div>
                </div>
                <span class="text-white text-sm font-medium" x-text="callStatus">Menghubungkan...</span>
            </div>
        </div>

        <!-- Avatar -->
        <div class="relative mb-6">
            <!-- Glow effect -->
            <div class="absolute -inset-8 bg-white/20 rounded-full blur-3xl" x-show="isConnected"></div>

            <!-- Pulse rings -->
            <template x-if="isConnected">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="absolute w-40 h-40 border-4 border-white/30 rounded-full pulse-ring"></div>
                    <div class="absolute w-48 h-48 border-4 border-white/20 rounded-full pulse-ring"
                        style="animation-delay: 0.5s"></div>
                </div>
            </template>

            <!-- Avatar Image -->
            <div class="relative">
                <img src="{{ asset('aruna-ai.png') }}" alt="Aruna AI"
                    class="relative w-32 h-32 rounded-full object-cover ring-8 ring-white/30 shadow-2xl">

                <!-- Speaking indicator -->
                <div class="absolute bottom-0 right-0 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center"
                    x-show="isConnected && isSpeaking">
                    <div class="flex items-center gap-0.5">
                        <div class="w-1 bg-primary-600 rounded-full audio-wave" style="animation-delay: 0s"></div>
                        <div class="w-1 bg-primary-600 rounded-full audio-wave" style="animation-delay: 0.2s"></div>
                        <div class="w-1 bg-primary-600 rounded-full audio-wave" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Name & Identity -->
        <h1 class="text-white text-3xl font-bold mb-2">Aruna AI</h1>
        <p class="text-white/80 text-sm" x-text="identity || 'Asisten Virtual Desa'"></p>

        <!-- Audio Visualizer (when speaking) -->
        <div class="mt-8 flex items-center gap-1 h-12" x-show="isConnected && isSpeaking" x-transition>
            <template x-for="i in 20" :key="i">
                <div class="w-1 bg-white/40 rounded-full audio-wave"
                    :style="`animation-delay: ${i * 0.05}s; height: ${Math.random() * 60 + 20}%`">
                </div>
            </template>
        </div>
    </div>

    <!-- Bottom Controls -->
    <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/30 to-transparent">
        <div class="flex items-center justify-center gap-6">
            <!-- Mute Button -->
            <button @click="toggleMute()"
                class="w-16 h-16 rounded-full flex items-center justify-center transition-all shadow-xl"
                :class="isMuted ? 'bg-red-500 hover:bg-red-600' : 'bg-white/20 backdrop-blur-md hover:bg-white/30'"
                x-show="isConnected">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!isMuted">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                </svg>
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="isMuted"
                    x-cloak>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"
                        clip-rule="evenodd" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                </svg>
            </button>

            <!-- Hang Up Button (always red, no call button) -->
            <button @click="hangUp()"
                class="w-20 h-20 rounded-full flex items-center justify-center shadow-2xl transform transition-all hover:scale-105 active:scale-95 bg-red-500 hover:bg-red-600"
                x-show="isConnected || isConnecting">
                <!-- Hang Up Icon -->
                <svg x-show="isConnected" class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 9c-1.6 0-3.15.25-4.6.72v3.1c0 .39-.23.74-.56.9-.98.49-1.87 1.12-2.66 1.85-.18.18-.43.28-.7.28-.28 0-.53-.11-.71-.29L.29 13.08c-.18-.17-.29-.42-.29-.7 0-.28.11-.53.29-.71C3.34 8.78 7.46 7 12 7s8.66 1.78 11.71 4.67c.18.18.29.43.29.71 0 .28-.11.53-.29.71l-2.48 2.48c-.18.18-.43.29-.71.29-.27 0-.52-.11-.7-.28-.79-.74-1.69-1.36-2.67-1.85-.33-.16-.56-.5-.56-.9v-3.1C15.15 9.25 13.6 9 12 9z" />
                </svg>
                <!-- Loading -->
                <svg x-show="isConnecting" class="animate-spin w-8 h-8 text-white" fill="none" viewBox="0 0 24 24"
                    x-cloak>
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
            </button>

            <!-- Speaker Button -->
            <button @click="toggleSpeaker()
                " class="w-16 h-16 rounded-full flex items-center justify-center transition-all shadow-xl"
                :class="speakerOn ? 'bg-white/30 backdrop-blur-md' : 'bg-white/20 backdrop-blur-md hover:bg-white/30'"
                x-show="isConnected">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Hidden audio elements will be added here by LiveKit -->
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/livekit-client@2/dist/livekit-client.umd.min.js"></script>
<script>
    function phoneCall() {
    return {
        // State
        isConnected: false,
        isConnecting: false,
        isMuted: false,
        speakerOn: true,
        isSpeaking: false,
        callStatus: 'Memulai panggilan...',
        identity: '{{ auth()->user()->name }}',
        roomName: '',
        callDuration: 0,

        // LiveKit
        roomRef: null,
        durationInterval: null,

        init() {
            console.log('Phone call app ready');
        },

        formatDuration(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
        },

        async autoStartCall() {
            // Auto-start call after a short delay
            await new Promise(resolve => setTimeout(resolve, 500));
            await this.startCall();
        },

        async startCall() {
            try {
                this.isConnecting = true;
                this.callStatus = 'Menghubungkan...';
                console.log('Starting call...');

                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                // 1) Get token (proxy dulu → fallback direct bila gagal)
                const q = new URLSearchParams();
                q.set('identity', this.identity);

                let token, room, url;
                try {
                    const tokenRes = await fetch(`{{ route('voice.token') }}?${q.toString()}`, {
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (!tokenRes.ok) throw new Error(`Proxy token gagal: ${tokenRes.status}`);
                    ({ token, room, url } = await tokenRes.json());
                    console.log(`Token OK via proxy → room=${room}`);
                } catch (err) {
                    console.warn('Proxy token gagal, coba direct...', err);
                    const tokenRes2 = await fetch(`https://voice.deltabhumi.co.id/getToken?${q.toString()}`);
                    if (!tokenRes2.ok) throw new Error(`Token gagal: ${tokenRes2.status}`);
                    ({ token, room, url } = await tokenRes2.json());
                    console.log(`Token OK via direct → room=${room}`);
                }
                this.roomName = room;

                // 2) Get microphone
                console.log('Requesting microphone access...');
                const media = await navigator.mediaDevices.getUserMedia({
                    audio: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true
                    },
                    video: false
                });
                const audioTrack = media.getAudioTracks()[0];
                console.log(`Microphone OK: ${audioTrack.label}`);

                // 3) Connect to LiveKit
                const { Room, RoomEvent, Track } = window.LivekitClient || window.LiveKit;
                this.roomRef = new Room({
                    audioCaptureDefaults: {
                        echoCancellation: true,
                        noiseSuppression: true,
                        autoGainControl: true
                    }
                });

                // Setup events
                this.roomRef.on(RoomEvent.Connected, () => {
                    this.isConnected = true;
                    this.isConnecting = false;
                    this.callStatus = 'Terhubung';
                    this.startDurationTimer();
                    console.log('Connected to room');
                });

                this.roomRef.on(RoomEvent.ParticipantConnected, (participant) => {
                    console.log(`${participant.identity} joined`);
                    if (participant.identity?.toString().toLowerCase().includes('aruna')
                        || participant.identity?.toString().toLowerCase().includes('agent')) {
                        this.callStatus = 'Aruna AI aktif';
                    }
                });

                this.roomRef.on(RoomEvent.TrackSubscribed, (track, publication, participant) => {
                    console.log(`Audio from ${participant.identity}`);
                    if (track.kind === 'audio') {
                        const el = document.createElement('audio');
                        el.autoplay = true;
                        el.playsInline = true; // iOS Safari
                        track.attach(el);
                        document.body.appendChild(el);
                        // coba play, bila diblokir user gesture diperlukan
                        el.play?.().catch(() => {});
                        this.isSpeaking = true;
                        setTimeout(() => this.isSpeaking = false, 2000);
                    }
                });

                this.roomRef.on(RoomEvent.Disconnected, () => {
                    this.isConnected = false;
                    this.callStatus = 'Terputus';
                    this.stopDurationTimer();
                    console.log('Call ended');
                });

                // Connect
                await this.roomRef.connect(url, token);

                // 4) Publish mic
                await this.roomRef.localParticipant.publishTrack(audioTrack, {
                    name: 'microphone',
                    source: Track.Source.Microphone
                });
                console.log('Microphone published');

                // 5) Invite Aruna (proxy dulu → fallback direct bila gagal)
                console.log('Inviting Aruna AI...');
                let inv = await fetch(`{{ route('voice.invite') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({ room })
                });

                if (!inv.ok) {
                    console.warn('Proxy invite gagal, coba direct...', inv.status);
                    inv = await fetch('https://voice.deltabhumi.co.id/invite', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ room })
                    });
                }

                if (!inv.ok) {
                    throw new Error(`Undangan gagal: ${inv.status}`);
                }
                console.log('Aruna AI will join shortly');

            } catch (e) {
                console.error(`Error: ${e.message}`);
                this.isConnecting = false;
                this.callStatus = 'Gagal terhubung';
                alert('Gagal memulai panggilan: ' + e.message);

                // Redirect back after error
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard") }}';
                }, 2000);
            }
        },

        async hangUp() {
            try {
                if (this.roomRef) {
                    await this.roomRef.disconnect();
                }
                this.isConnected = false;
                this.isConnecting = false;
                this.callStatus = 'Panggilan diakhiri';
                this.stopDurationTimer();
                console.log('Call ended by user');

                // Redirect after 1 second
                setTimeout(() => {
                    window.location.href = '{{ route("dashboard") }}';
                }, 1000);
            } catch (e) {
                console.error(`Error: ${e.message}`);
            }
        },

        toggleMute() {
            this.isMuted = !this.isMuted;
            if (this.roomRef) {
                this.roomRef.localParticipant.setMicrophoneEnabled(!this.isMuted);
                console.log(this.isMuted ? 'Microphone muted' : 'Microphone unmuted');
            }
        },

        toggleSpeaker() {
            this.speakerOn = !this.speakerOn;
            console.log(this.speakerOn ? 'Speaker on' : 'Speaker off');
            // Note: Speaker control via Web Audio API would be more complex
        },

        startDurationTimer() {
            this.callDuration = 0;
            this.durationInterval = setInterval(() => {
                this.callDuration++;
            }, 1000);
        },

        stopDurationTimer() {
            if (this.durationInterval) {
                clearInterval(this.durationInterval);
                this.durationInterval = null;
            }
        }
    }
}
</script>
@endpush