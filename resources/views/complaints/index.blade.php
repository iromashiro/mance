@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto">

    {{-- Hero Section --}}
    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-600 to-accent-600 shadow-2xl mb-8">
        <!-- Animated Background -->
        <div class="absolute inset-0">
            <div class="absolute top-10 right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-10 left-10 w-60 h-60 bg-accent-400/10 rounded-full blur-3xl animate-float">
            </div>
        </div>

        <div class="relative p-8 lg:p-12">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="h-2 w-2 bg-success-400 rounded-full animate-pulse"></div>
                        <span class="text-white/90 text-sm font-medium">Sistem Pengaduan Online</span>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-heading font-bold text-white mb-4 animate-slide-up">
                        Suara Anda, Solusi Kami 📢
                    </h1>
                    <p class="text-lg text-white/90 max-w-2xl mb-6">
                        Laporkan permasalahan di sekitar Anda. Kami akan memproses setiap pengaduan dengan cepat,
                        transparan, dan berkomitmen untuk memberikan solusi terbaik.
                    </p>
                    <a href="{{ route('complaints.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-white text-primary-700 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Buat Pengaduan Baru
                    </a>
                </div>

                <!-- Stats -->
                <div class="mt-8 lg:mt-0 lg:ml-10">
                    <div class="grid grid-cols-2 gap-4">
                        @php
                        $totalComplaints = Auth::user()->complaints()->count();
                        $completedComplaints = Auth::user()->complaints()->where('status', 'completed')->count();
                        $completionRate = $totalComplaints > 0 ? round(($completedComplaints / $totalComplaints) * 100)
                        : 0;
                        @endphp

                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-3xl font-bold text-white">{{ $totalComplaints }}</div>
                            <div class="text-white/80 text-sm">Total Laporan</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-3xl font-bold text-white">{{ $completionRate }}%</div>
                            <div class="text-white/80 text-sm">Terselesaikan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Pills with Counts --}}
    <div class="mb-8">
        <div class="flex flex-wrap gap-2">
            @php
            $statuses = [
            'all' => ['label' => 'Semua', 'count' => Auth::user()->complaints()->count(), 'icon' => '📋'],
            'pending' => ['label' => 'Menunggu', 'count' => Auth::user()->complaints()->where('status',
            'pending')->count(), 'icon' => '⏳'],
            'process' => ['label' => 'Diproses', 'count' => Auth::user()->complaints()->where('status',
            'process')->count(), 'icon' => '🔄'],
            'completed' => ['label' => 'Selesai', 'count' => Auth::user()->complaints()->where('status',
            'completed')->count(), 'icon' => '✅'],
            'rejected' => ['label' => 'Ditolak', 'count' => Auth::user()->complaints()->where('status',
            'rejected')->count(), 'icon' => '❌'],
            ];
            @endphp

            @foreach($statuses as $key => $status)
            <a href="{{ $key === 'all' ? route('complaints.index') : route('complaints.index', ['status' => $key]) }}"
                class="group relative inline-flex items-center px-4 py-2 rounded-full font-medium text-sm transition-all transform hover:scale-105
                       @if(($key === 'all' && !request('status')) || request('status') == $key)
                           bg-gradient-to-r from-primary-500 to-accent-500 text-white shadow-lg
                       @else
                           bg-white text-gray-700 hover:bg-gray-50 shadow-md
                       @endif">
                <span class="mr-1">{{ $status['icon'] }}</span>
                <span>{{ $status['label'] }}</span>
                @if($status['count'] > 0)
                <span class="ml-2 inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-bold
                           @if(($key === 'all' && !request('status')) || request('status') == $key)
                               bg-white/20 text-white
                           @else
                               bg-primary-100 text-primary-700
                           @endif">
                    {{ $status['count'] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- Complaints Grid --}}
    @if($complaints->count() > 0)
    <div class="grid gap-6">
        @foreach($complaints as $complaint)
        <a href="{{ route('complaints.show', $complaint) }}" class="group relative block">
            <!-- Hover Glow Effect -->
            <div
                class="absolute -inset-0.5 bg-gradient-to-r from-primary-400 to-accent-400 rounded-2xl blur opacity-0 group-hover:opacity-20 transition-all duration-300">
            </div>

            <!-- Card Content -->
            <div class="relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6">
                <div class="flex items-start justify-between">
                    <!-- Main Content -->
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-center space-x-3 mb-3">
                            <!-- Status Icon -->
                            <div @class([ 'h-10 w-10 rounded-xl flex items-center justify-center'
                                , 'bg-gradient-to-br from-success-400 to-teal-500'=> $complaint->status === 'completed',
                                'bg-gradient-to-br from-yellow-400 to-orange-500' => $complaint->status === 'process',
                                'bg-gradient-to-br from-red-400 to-pink-500' => $complaint->status === 'rejected',
                                'bg-gradient-to-br from-gray-400 to-gray-500' => $complaint->status === 'pending',
                                ])>
                                @if($complaint->status === 'completed')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                @elseif($complaint->status === 'process')
                                <svg class="h-5 w-5 text-white animate-spin" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                @elseif($complaint->status === 'rejected')
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                @else
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @endif
                            </div>

                            <!-- Ticket Number -->
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $complaint->ticket_number }}</p>
                                <p class="text-xs text-gray-500">{{ $complaint->created_at->format('d M Y, H:i') }}</p>
                            </div>

                            <!-- Status Badge -->
                            <span
                                @class([ 'ml-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold'
                                , 'bg-gradient-to-r from-success-100 to-teal-100 text-success-800'=> $complaint->status
                                === 'completed',
                                'bg-gradient-to-r from-yellow-100 to-orange-100 text-yellow-800' => $complaint->status
                                === 'process',
                                'bg-gradient-to-r from-red-100 to-pink-100 text-red-800' => $complaint->status ===
                                'rejected',
                                'bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800' => $complaint->status ===
                                'pending',
                                ])>
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

                        <!-- Category -->
                        <div
                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-50 text-primary-700 mb-3">
                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            {{ $complaint->category->name }}
                        </div>

                        <!-- Description -->
                        <p class="text-gray-700 mb-4 line-clamp-2 group-hover:text-gray-900 transition-colors">
                            {{ $complaint->description }}
                        </p>

                        <!-- Footer Stats -->
                        <div class="flex items-center space-x-6 text-sm">
                            @if($complaint->responses()->count() > 0)
                            <span class="flex items-center text-gray-500">
                                <svg class="mr-1.5 h-4 w-4 text-primary-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                </svg>
                                {{ $complaint->responses()->count() }} Tanggapan
                            </span>
                            @endif

                            @if($complaint->votes()->count() > 0)
                            <span class="flex items-center text-gray-500">
                                <svg class="mr-1.5 h-4 w-4 text-success-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                                {{ $complaint->votes()->count() }} Dukungan
                            </span>
                            @endif

                            <span class="flex items-center text-gray-500">
                                <svg class="mr-1.5 h-4 w-4 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ $complaint->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>

                    <!-- Arrow -->
                    <div class="ml-6 flex-shrink-0 self-center">
                        <div
                            class="h-10 w-10 rounded-full bg-gray-50 group-hover:bg-primary-50 flex items-center justify-center transition-all group-hover:scale-110">
                            <svg class="h-5 w-5 text-gray-400 group-hover:text-primary-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                @if($complaint->status === 'process')
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
                        <span>Progress</span>
                        <span>50%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gradient-to-r from-primary-400 to-accent-400 h-2 rounded-full animate-pulse"
                            style="width: 50%"></div>
                    </div>
                </div>
                @endif
            </div>
        </a>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $complaints->links() }}
    </div>

    @else
    {{-- Empty State --}}
    <div class="text-center py-16">
        <div
            class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 mb-6">
            <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada pengaduan</h3>
        <p class="text-gray-500 max-w-md mx-auto mb-6">
            @if(request('status'))
            Tidak ada pengaduan dengan status "{{ ucfirst(request('status')) }}".
            Coba filter lain atau buat pengaduan baru.
            @else
            Mulai laporkan masalah di sekitar Anda. Suara Anda penting untuk perubahan yang lebih baik.
            @endif
        </p>
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('complaints.create') }}" class="btn btn-primary">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Buat Pengaduan Pertama
            </a>
            @if(request('status'))
            <a href="{{ route('complaints.index') }}" class="btn btn-secondary">
                <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                Reset Filter
            </a>
            @endif
        </div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .line-clamp-2 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
</style>
@endpush
@endsection
