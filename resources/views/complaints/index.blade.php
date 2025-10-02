@extends('layouts.app')

@section('title', 'Daftar Pengaduan')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900">Daftar Pengaduan Saya</h1>
            <a href="{{ route('complaints.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Pengaduan Baru
            </a>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow mb-6">
        <nav class="flex space-x-1 p-1" aria-label="Filter">
            <a href="{{ route('complaints.index') }}"
                class="{{ !request('status') ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium">
                Semua ({{ Auth::user()->complaints()->count() }})
            </a>
            <a href="{{ route('complaints.index', ['status' => 'pending']) }}"
                class="{{ request('status') == 'pending' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium">
                Menunggu ({{ Auth::user()->complaints()->where('status', 'pending')->count() }})
            </a>
            <a href="{{ route('complaints.index', ['status' => 'process']) }}"
                class="{{ request('status') == 'process' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium">
                Diproses ({{ Auth::user()->complaints()->where('status', 'process')->count() }})
            </a>
            <a href="{{ route('complaints.index', ['status' => 'completed']) }}"
                class="{{ request('status') == 'completed' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium">
                Selesai ({{ Auth::user()->complaints()->where('status', 'completed')->count() }})
            </a>
            <a href="{{ route('complaints.index', ['status' => 'rejected']) }}"
                class="{{ request('status') == 'rejected' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700' }} px-3 py-2 rounded-md text-sm font-medium">
                Ditolak ({{ Auth::user()->complaints()->where('status', 'rejected')->count() }})
            </a>
        </nav>
    </div>

    <!-- Complaints List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($complaints->count() > 0)
        <ul class="divide-y divide-gray-200">
            @foreach($complaints as $complaint)
            <li>
                <a href="{{ route('complaints.show', $complaint) }}" class="block hover:bg-gray-50 px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-primary-600 truncate">
                                    {{ $complaint->ticket_number }}
                                </p>
                                <div class="ml-2 flex-shrink-0">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $complaint->status == 'completed' ? 'bg-green-100 text-green-800' :
                                                   ($complaint->status == 'process' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($complaint->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        @if($complaint->status == 'pending')
                                        Menunggu
                                        @elseif($complaint->status == 'process')
                                        Diproses
                                        @elseif($complaint->status == 'completed')
                                        Selesai
                                        @elseif($complaint->status == 'rejected')
                                        Ditolak
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">{{ $complaint->category->name }}</span>
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ Str::limit($complaint->description, 100) }}
                                </p>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $complaint->created_at->format('d M Y, H:i') }}

                                    @if($complaint->responses()->count() > 0)
                                    <span class="ml-4 flex items-center">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                            </path>
                                        </svg>
                                        {{ $complaint->responses()->count() }} Tanggapan
                                    </span>
                                    @endif

                                    @if($complaint->votes()->count() > 0)
                                    <span class="ml-4 flex items-center">
                                        <svg class="flex-shrink-0 mr-1.5 h-4 w-4 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5">
                                            </path>
                                        </svg>
                                        {{ $complaint->votes()->count() }} Dukungan
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="ml-5 flex-shrink-0">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </a>
            </li>
            @endforeach
        </ul>

        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $complaints->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pengaduan</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if(request('status'))
                Tidak ada pengaduan dengan status ini.
                @else
                Mulai dengan membuat pengaduan baru.
                @endif
            </p>
            <div class="mt-6">
                <a href="{{ route('complaints.create') }}"
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Pengaduan
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection