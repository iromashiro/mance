@extends('layouts.app')
@section('title', 'Aruna AI MEMBARA')
@section('content')

<iframe class="chat-frame" src="https://aruna.muaraenimkab.go.id/chat/nSbYX9ZXyIebM9dI" title="Aruna AI Chat"
    loading="lazy" allow="microphone; clipboard-write">
</iframe>

@push('styles')
<style>
    .chat-frame {
        width: 100%;
        height: clamp(420px, 80svh, 900px);
        /* fleksibel: min 420px, ideal 80% layar, max 900px */
        border: 0;
        display: block;
        border-radius: 12px;
    }
</style>
@endpush
@endsection