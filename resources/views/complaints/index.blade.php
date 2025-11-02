@extends('layouts.app')

@section('title', 'Pengaduan')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header Section --}}
    <div class="bg-white border-b border-gray-200">
        <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl font-semibold text-gray-900">
                        Daftar Pengaduan
                    </h1>
                    <p class="mt-2 text-sm text-gray-500">
                        Kelola dan pantau status pengaduan Anda
                    </p>
                </div>
                <div class="mt-4 flex md:mt-0 md:ml-4 space-x-3">
                    <a href="{{ route('complaints.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Pengaduan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto py-8">
        {{-- Filter & Stats --}}
        <div class="mb-8 grid grid-cols-1 sm:grid-cols-4 gap-4">
            {{-- Status Filters --}}
            <a href="{{ route('complaints.index', ['status' => '']) }}"
                class="@if(!request('status')) bg-primary-50 border-primary-300 @else bg-white border-gray-200 hover:border-gray-300 @endif border rounded-lg p-4 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Semua</p>
                        <p class="text-2xl font-bold text-gray-900">{{ Auth::user()->complaints()->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('complaints.index', ['status' => 'pending']) }}"
                class="@if(request('status') == 'pending') bg-yellow-50 border-yellow-300 @else bg-white border-gray-200 hover:border-gray-300 @endif border rounded-lg p-4 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Pending</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ Auth::user()->complaints()->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('complaints.index', ['status' => 'process']) }}"
                class="@if(request('status') == 'process') bg-blue-50 border-blue-300 @else bg-white border-gray-200 hover:border-gray-300 @endif border rounded-lg p-4 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Diproses</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ Auth::user()->complaints()->where('status', 'process')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600 animate-spin" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
            </a>

            <a href="{{ route('complaints.index', ['status' => 'completed']) }}"
                class="@if(request('status') == 'completed') bg-success-50 border-success-300 @else bg-white border-gray-200 hover:border-gray-300 @endif border rounded-lg p-4 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase">Selesai</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ Auth::user()->complaints()->where('status', 'completed')->count() }}</p>
                    </div>
                    <div class="w-10 h-10 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        {{-- Complaints List --}}
        @if($complaints->count() > 0)
        <div class="space-y-4">
            @foreach($complaints as $complaint)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            {{-- Header --}}
                            <div class="flex items-start space-x-3">
                                <div @class([ 'flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center'
                                    , 'bg-success-100'=> $complaint->status === 'completed',
                                    'bg-yellow-100' => $complaint->status === 'pending',
                                    'bg-blue-100' => $complaint->status === 'process',
                                    'bg-red-100' => $complaint->status === 'rejected',
                                    'bg-gray-100' => !in_array($complaint->status,
                                    ['completed','pending','process','rejected']),
                                    ])>
                                    @if($complaint->status === 'completed')
                                    <svg class="w-5 h-5 text-success-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    @elseif($complaint->status === 'process')
                                    <svg class="w-5 h-5 text-blue-600 animate-spin" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    @elseif($complaint->status === 'rejected')
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    @else
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            #{{ $complaint->ticket_number }}
                                        </h3>
                                        <span @class([ 'px-2.5 py-0.5 text-xs font-medium rounded-lg'
                                            , 'bg-success-100 text-success-700'=> $complaint->status === 'completed',
                                            'bg-yellow-100 text-yellow-700' => $complaint->status === 'pending',
                                            'bg-blue-100 text-blue-700' => $complaint->status === 'process',
                                            'bg-red-100 text-red-700' => $complaint->status === 'rejected',
                                            'bg-gray-100 text-gray-700' => !in_array($complaint->status,
                                            ['completed','pending','process','rejected']),
                                            ])>
                                            {{ ucfirst($complaint->status) }}
                                        </span>
                                        @if($complaint->priority)
                                        <span @class([ 'px-2.5 py-0.5 text-xs font-medium rounded-lg'
                                            , 'bg-red-100 text-red-700'=> $complaint->priority === 'high',
                                            'bg-yellow-100 text-yellow-700' => $complaint->priority === 'medium',
                                            'bg-gray-100 text-gray-700' => $complaint->priority === 'low',
                                            ])>
                                            {{ ucfirst($complaint->priority) }} Priority
                                        </span>
                                        @endif
                                    </div>

                                    <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                        <span>{{ $complaint->created_at->format('d M Y, H:i') }}</span>
                                        <span>•</span>
                                        <span>{{ $complaint->category->name ?? 'Umum' }}</span>
                                        @if($complaint->responses_count > 0)
                                        <span>•</span>
                                        <span>{{ $complaint->responses_count }} tanggapan</span>
                                        @endif
                                    </div>

                                    <p class="mt-3 text-sm text-gray-600 line-clamp-2">
                                        {{ $complaint->description }}
                                    </p>

                                    @if($complaint->images && count($complaint->images) > 0)
                                    <div class="mt-3 flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-xs text-gray-500">{{ count($complaint->images) }}
                                            lampiran</span>
                                    </div>
                                    @endif

                                    <div class="mt-4 flex items-center space-x-3">
                                        <a href="{{ route('complaints.show', $complaint) }}"
                                            class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700">
                                            Lihat Detail
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>

                                        @if($complaint->status === 'pending')
                                        <form method="POST" action="{{ route('complaints.destroy', $complaint) }}"
                                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengaduan ini?')"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm font-medium text-red-600 hover:text-red-700">
                                                Batalkan
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Side Info --}}
                        @if($complaint->updated_at > $complaint->created_at)
                        <div class="flex-shrink-0 ml-4">
                            <p class="text-xs text-gray-500">
                                Diperbarui: {{ $complaint->updated_at->diffForHumans() }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($complaints->hasPages())
        <div class="mt-8">
            {{ $complaints->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="text-center py-16 px-4 bg-white rounded-xl border border-gray-200">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-xl mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada pengaduan</h3>
            <p class="text-sm text-gray-500 mb-6">
                @if(request('status'))
                Tidak ada pengaduan dengan status "{{ ucfirst(request('status')) }}"
                @else
                Anda belum membuat pengaduan apapun
                @endif
            </p>
            <a href="{{ route('complaints.create') }}"
                class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Pengaduan
            </a>
        </div>
        @endif
    </div>
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