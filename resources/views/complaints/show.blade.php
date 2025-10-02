@extends('layouts.app')

@section('title', 'Detail Pengaduan')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('complaints.index') }}" class="mr-4">
                    <svg class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengaduan</h1>
            </div>
            <span class="px-3 py-1 text-sm font-medium rounded-full
                {{ $complaint->status == 'completed' ? 'bg-green-100 text-green-800' :
                   ($complaint->status == 'process' ? 'bg-yellow-100 text-yellow-800' :
                   ($complaint->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                @if($complaint->status == 'pending')
                Menunggu
                @elseif($complaint->status == 'process')
                Sedang Diproses
                @elseif($complaint->status == 'completed')
                Selesai
                @elseif($complaint->status == 'rejected')
                Ditolak
                @endif
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Complaint Details -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <!-- Ticket Info -->
                    <div class="mb-4 pb-4 border-b border-gray-200">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-lg font-medium text-gray-900">{{ $complaint->title }}</h2>
                                <p class="text-sm text-gray-500 mt-1">Tiket: {{ $complaint->ticket_number }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if(!$complaint->is_private)
                                <!-- Vote Button -->
                                <form action="{{ route('complaints.vote', $complaint) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1 border {{ $complaint->votes()->where('user_id', Auth::id())->exists() ? 'border-primary-600 bg-primary-50 text-primary-600' : 'border-gray-300 text-gray-700 bg-white hover:bg-gray-50' }} text-sm leading-5 font-medium rounded-md">
                                        <svg class="h-4 w-4 mr-1"
                                            fill="{{ $complaint->votes()->where('user_id', Auth::id())->exists() ? 'currentColor' : 'none' }}"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                            </path>
                                        </svg>
                                        {{ $complaint->votes()->count() }} Dukungan
                                    </button>
                                </form>
                                @endif

                                @if($complaint->is_private)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                    Privat
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Category & Priority -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kategori</p>
                            <p class="mt-1 text-sm text-gray-900">{{ $complaint->category->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Prioritas</p>
                            <p class="mt-1">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $complaint->priority == 'high' ? 'bg-red-100 text-red-800' :
                                       ($complaint->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ $complaint->priority == 'high' ? 'Tinggi' : ($complaint->priority == 'medium' ? 'Sedang' : 'Rendah') }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Lokasi</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $complaint->location }}</p>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-sm font-medium text-gray-500">Deskripsi Pengaduan</p>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ $complaint->description }}</p>
                    </div>

                    <!-- Images -->
                    @if($complaint->images()->count() > 0)
                    <div>
                        <p class="text-sm font-medium text-gray-500 mb-2">Foto/Bukti</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($complaint->images as $image)
                            <a href="{{ Storage::url($image->image_url) }}" target="_blank" class="group">
                                <img src="{{ Storage::url($image->image_url) }}" alt="Bukti pengaduan"
                                    class="h-32 w-full object-cover rounded-lg group-hover:opacity-75">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Responses -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tanggapan
                        ({{ $complaint->responses()->count() }})</h3>

                    @if($complaint->responses()->count() > 0)
                    <div class="space-y-4">
                        @foreach($complaint->responses as $response)
                        <div
                            class="border-l-4 {{ $response->user->role == 'super_admin' ? 'border-primary-500' : 'border-gray-300' }} pl-4">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $response->user->name }}
                                            @if($response->user->role == 'super_admin')
                                            <span
                                                class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-100 text-primary-800">
                                                Admin
                                            </span>
                                            @endif
                                        </p>
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600">{{ $response->response }}</p>
                                    <p class="mt-1 text-xs text-gray-400">{{ $response->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 text-sm">Belum ada tanggapan</p>
                    @endif

                    <!-- Add Response Form (if complaint is not completed/rejected) -->
                    @if(!in_array($complaint->status, ['completed', 'rejected']))
                    <div class="mt-6 border-t border-gray-200 pt-6">
                        <form action="{{ route('complaints.response', $complaint) }}" method="POST">
                            @csrf
                            <div>
                                <label for="response" class="block text-sm font-medium text-gray-700">
                                    Tambah Tanggapan
                                </label>
                                <textarea id="response" name="response" rows="3" required
                                    placeholder="Tulis tanggapan Anda..."
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500"></textarea>
                            </div>
                            <div class="mt-3">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700">
                                    Kirim Tanggapan
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Timeline -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Timeline</h3>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <!-- Created -->
                            <li class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                    aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm text-gray-900">Pengaduan dibuat</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $complaint->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </li>

                            <!-- Process -->
                            @if($complaint->processed_at)
                            <li class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                    aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            class="h-8 w-8 rounded-full bg-yellow-400 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm text-gray-900">Diproses oleh admin</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $complaint->processed_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>
                            </li>
                            @endif

                            <!-- Completed/Rejected -->
                            @if($complaint->completed_at)
                            <li class="relative">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span
                                            class="h-8 w-8 rounded-full {{ $complaint->status == 'completed' ? 'bg-green-400' : 'bg-red-400' }} flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                @if($complaint->status == 'completed')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                                @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                                @endif
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm text-gray-900">
                                            {{ $complaint->status == 'completed' ? 'Selesai' : 'Ditolak' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $complaint->completed_at->format('d M Y, H:i') }}</p>
                                        @if($complaint->admin_notes)
                                        <p class="text-sm text-gray-600 mt-1">{{ $complaint->admin_notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Pelapor</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $complaint->user->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $complaint->user->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">No. Telepon</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $complaint->user->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Kategori Masyarakat</dt>
                            <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $complaint->user->category }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection