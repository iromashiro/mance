@extends('layouts.admin')

@section('title', 'Detail Pengaduan')

@section('header', 'Detail Pengaduan')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('admin.complaints.index') }}"
        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Daftar Pengaduan
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Complaint Info Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-primary-600 to-accent-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $complaint->title }}</h2>
                        <p class="text-sm text-white/80 mt-1">{{ $complaint->ticket_number }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($complaint->status == 'pending')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-white/20 text-white">
                            Menunggu
                        </span>
                        @elseif($complaint->status == 'process')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Diproses
                        </span>
                        @elseif($complaint->status == 'completed')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Selesai
                        </span>
                        @elseif($complaint->status == 'rejected')
                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Ditolak
                        </span>
                        @endif

                        <span
                            class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $complaint->priority == 'high' ? 'bg-red-100 text-red-800' :
                               ($complaint->priority == 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ $complaint->priority == 'high' ? 'Tinggi' : ($complaint->priority == 'medium' ? 'Sedang' : 'Rendah') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- User Info -->
                <div class="flex items-center mb-6 pb-6 border-b border-gray-200">
                    <div
                        class="h-12 w-12 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-white font-semibold text-lg">
                        {{ substr($complaint->user->name, 0, 2) }}
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-gray-900">{{ $complaint->user->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $complaint->user->email }}</p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $complaint->created_at->format('d M Y, H:i') }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $complaint->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- Category & Location -->
                <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Kategori</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $complaint->category->name }}</p>
                    </div>
                    @if($complaint->location)
                    <div>
                        <p class="text-xs font-medium text-gray-500 mb-1">Lokasi</p>
                        <p class="text-sm text-gray-900">{{ $complaint->location }}</p>
                    </div>
                    @endif
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Deskripsi</h3>
                    <div class="prose prose-sm max-w-none text-gray-700">
                        {!! nl2br(e($complaint->description)) !!}
                    </div>
                </div>

                <!-- Images -->
                @if($complaint->images->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 mb-3">Foto Pendukung</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($complaint->images as $image)
                        <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank"
                            class="group relative aspect-square rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Bukti"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            <div
                                class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-center justify-center">
                                <svg class="h-8 w-8 text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                    </path>
                                </svg>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Responses Section -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Tanggapan ({{ $complaint->responses->count() }})</h3>
            </div>

            <div class="p-6">
                @forelse($complaint->responses as $response)
                <div class="flex items-start space-x-3 {{ !$loop->last ? 'mb-6 pb-6 border-b border-gray-200' : '' }}">
                    <div class="flex-shrink-0">
                        <div
                            class="h-10 w-10 rounded-full {{ $response->user->role === 'super_admin' ? 'bg-primary-500' : 'bg-gray-400' }} flex items-center justify-center text-white font-semibold">
                            {{ substr($response->user->name, 0, 2) }}
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $response->user->name }}</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $response->user->role === 'super_admin' ? 'Administrator' : 'Pelapor' }}
                                </p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $response->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">
                            {!! nl2br(e($response->response)) !!}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <p class="text-gray-500 text-sm">Belum ada tanggapan</p>
                </div>
                @endforelse

                <!-- Add Response Form -->
                @if($complaint->status !== 'completed' && $complaint->status !== 'rejected')
                <form method="POST" action="{{ route('admin.complaints.respond', $complaint) }}"
                    class="mt-6 pt-6 border-t border-gray-200">
                    @csrf
                    <label for="response" class="block text-sm font-medium text-gray-700 mb-2">Tambah Tanggapan</label>
                    <textarea name="response" id="response" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Tulis tanggapan Anda..." required></textarea>
                    @error('response')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-3 flex justify-end">
                        <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                            Kirim Tanggapan
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Aksi</h3>
            </div>
            <div class="p-4 space-y-3">
                <a href="{{ route('admin.complaints.edit', $complaint) }}"
                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-primary-600 text-primary-600 rounded-lg hover:bg-primary-50 transition-colors">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Status & Prioritas
                </a>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Statistik</h3>
            </div>
            <div class="p-4 space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Total Tanggapan</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $complaint->responses->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Dukungan</span>
                    <span class="text-sm font-semibold text-gray-900">{{ $complaint->votes->count() }}</span>
                </div>
                @if($complaint->processed_at)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Diproses Pada</span>
                    <span
                        class="text-sm font-semibold text-gray-900">{{ $complaint->processed_at->format('d M Y') }}</span>
                </div>
                @endif
                @if($complaint->completed_at)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Selesai Pada</span>
                    <span
                        class="text-sm font-semibold text-gray-900">{{ $complaint->completed_at->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Admin Notes Card -->
        @if($complaint->admin_notes)
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                <h3 class="text-sm font-semibold text-gray-900">Catatan Admin</h3>
            </div>
            <div class="p-4">
                <p class="text-sm text-gray-700">{!! nl2br(e($complaint->admin_notes)) !!}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
