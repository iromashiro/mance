@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
        class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
        {{ session('success') }}
        <button @click="show = false" class="absolute top-0 right-0 px-4 py-3">
            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    <div class="bg-white shadow rounded-lg">
        <!-- Profile Header -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 px-6 py-8 rounded-t-lg">
            <div class="flex items-center space-x-4">
                <div class="h-20 w-20 bg-white rounded-full flex items-center justify-center">
                    <span class="text-3xl font-bold text-primary-600">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </span>
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-primary-100">{{ Auth::user()->email }}</p>
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800 mt-2">
                        {{ ucfirst(Auth::user()->category) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div x-data="{ activeTab: 'info' }" class="px-6">
            <nav class="flex space-x-8 border-b border-gray-200 -mb-px" aria-label="Tabs">
                <button @click="activeTab = 'info'"
                    :class="activeTab === 'info' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Informasi Pribadi
                </button>
                <button @click="activeTab = 'security'"
                    :class="activeTab === 'security' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Keamanan
                </button>
                <button @click="activeTab = 'activity'"
                    :class="activeTab === 'activity' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Aktivitas
                </button>
            </nav>

            <!-- Tab Content -->
            <div class="py-6">
                <!-- Personal Info Tab -->
                <div x-show="activeTab === 'info'" x-cloak>
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">
                                    Nama Lengkap
                                </label>
                                <input type="text" name="name" id="name" value="{{ Auth::user()->name }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">
                                    Email
                                </label>
                                <input type="email" name="email" id="email" value="{{ Auth::user()->email }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">
                                    Nomor Telepon
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ Auth::user()->phone }}"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">
                                    Kategori Masyarakat
                                </label>
                                <select name="category" id="category"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                    <option value="pelajar" {{ Auth::user()->category == 'pelajar' ? 'selected' : '' }}>
                                        Pelajar</option>
                                    <option value="pegawai" {{ Auth::user()->category == 'pegawai' ? 'selected' : '' }}>
                                        Pegawai</option>
                                    <option value="pencari_kerja"
                                        {{ Auth::user()->category == 'pencari_kerja' ? 'selected' : '' }}>Pencari Kerja
                                    </option>
                                    <option value="pengusaha"
                                        {{ Auth::user()->category == 'wirausaha' ? 'selected' : '' }}>Wirausaha</option>
                                    <option value="umum" {{ Auth::user()->category == 'umum' ? 'selected' : '' }}>Umum
                                    </option>
                                </select>
                                @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" x-cloak>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700">
                                    Password Saat Ini
                                </label>
                                <input type="password" name="current_password" id="current_password"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">
                                    Password Baru
                                </label>
                                <input type="password" name="password" id="password"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                                    Konfirmasi Password Baru
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    Ubah Password
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Activity Tab -->
                <div x-show="activeTab === 'activity'" x-cloak>
                    <div class="space-y-6">
                        <!-- Statistics -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 rounded-full p-2">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-500">Total Pengaduan</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ Auth::user()->complaints()->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-500">Pengaduan Selesai</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ Auth::user()->complaints()->where('status', 'completed')->count() }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="bg-yellow-100 rounded-full p-2">
                                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-500">Layanan Favorit</p>
                                        <p class="text-lg font-semibold text-gray-900">
                                            {{ Auth::user()->favorites()->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activities -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Terbaru</h3>
                            <div class="bg-gray-50 rounded-lg overflow-hidden">
                                @php
                                $activities = Auth::user()->activities()->latest()->take(10)->get();
                                @endphp
                                @if($activities->count() > 0)
                                <ul class="divide-y divide-gray-200">
                                    @foreach($activities as $activity)
                                    <li class="px-4 py-3">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900">{{ $activity->activity_type }}</p>
                                                <p class="text-xs text-gray-500">{{ $activity->description }}</p>
                                            </div>
                                            <span class="text-xs text-gray-400">
                                                @php
                                                $when = $activity->created_at instanceof \Illuminate\Support\Carbon
                                                ? $activity->created_at->diffForHumans()
                                                :
                                                (\Illuminate\Support\Carbon::parse($activity->created_at)->diffForHumans());
                                                @endphp
                                                {{ $when }}
                                            </span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="p-4 text-sm text-gray-500">Belum ada aktivitas</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
