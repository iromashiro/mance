@extends('layouts.admin')

@section('title', 'Edit Pengaduan')

@section('header', 'Edit Pengaduan')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Complaint Info -->
    <div class="bg-white shadow rounded-lg mb-6 p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $complaint->title }}</h2>
                <p class="text-sm text-gray-500 mt-1">Tiket: {{ $complaint->ticket_number }}</p>
            </div>
            <a href="{{ route('admin.complaints.show', $complaint) }}"
                class="text-primary-600 hover:text-primary-800 text-sm">
                ← Kembali ke Detail
            </a>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-500">Pelapor:</span>
                <span class="ml-2 font-medium">{{ $complaint->user->name }}</span>
            </div>
            <div>
                <span class="text-gray-500">Kategori:</span>
                <span class="ml-2 font-medium">{{ $complaint->category->name }}</span>
            </div>
            <div>
                <span class="text-gray-500">Dibuat:</span>
                <span class="ml-2">{{ $complaint->created_at->format('d M Y H:i') }}</span>
            </div>
            <div>
                <span class="text-gray-500">Total Voting:</span>
                <span class="ml-2 font-medium">{{ $complaint->votes->count() }}</span>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.complaints.update', $complaint) }}" method="POST"
        class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status Pengaduan <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="pending" {{ $complaint->status == 'pending' ? 'selected' : '' }}>
                        Menunggu - Belum ditangani
                    </option>
                    <option value="process" {{ $complaint->status == 'process' ? 'selected' : '' }}>
                        Diproses - Sedang ditangani
                    </option>
                    <option value="completed" {{ $complaint->status == 'completed' ? 'selected' : '' }}>
                        Selesai - Telah diselesaikan
                    </option>
                    <option value="rejected" {{ $complaint->status == 'rejected' ? 'selected' : '' }}>
                        Ditolak - Tidak dapat ditangani
                    </option>
                </select>
                @error('status')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                    Prioritas <span class="text-red-500">*</span>
                </label>
                <select name="priority" id="priority" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="low" {{ $complaint->priority == 'low' ? 'selected' : '' }}>
                        Rendah - Tidak mendesak
                    </option>
                    <option value="medium" {{ $complaint->priority == 'medium' ? 'selected' : '' }}>
                        Sedang - Perlu perhatian
                    </option>
                    <option value="high" {{ $complaint->priority == 'high' ? 'selected' : '' }}>
                        Tinggi - Segera ditangani
                    </option>
                </select>
                @error('priority')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Admin Notes -->
            <div>
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Admin (Internal)
                </label>
                <textarea name="admin_notes" id="admin_notes" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Catatan internal untuk admin lainnya...">{{ old('admin_notes', $complaint->admin_notes) }}</textarea>
                <p class="mt-1 text-sm text-gray-500">
                    Catatan ini hanya terlihat oleh admin dan tidak akan dilihat oleh user
                </p>
                @error('admin_notes')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Complaint Details (Read-only) -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold mb-4">Detail Pengaduan</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                        <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-700">
                            {{ $complaint->description }}
                        </div>
                    </div>

                    @if($complaint->location)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi</label>
                        <div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-700">
                            {{ $complaint->location }}
                        </div>
                    </div>
                    @endif

                    @if($complaint->images->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Foto Bukti</label>
                        <div class="grid grid-cols-3 gap-4">
                            @foreach($complaint->images as $image)
                            <a href="{{ Storage::url($image->image_path) }}" target="_blank" class="group">
                                <img src="{{ Storage::url($image->image_path) }}" alt="Bukti"
                                    class="w-full h-32 object-cover rounded-lg group-hover:opacity-90 transition">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('admin.complaints.show', $complaint) }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <!-- Quick Actions -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
        <h3 class="text-sm font-semibold text-yellow-800 mb-2">💡 Tips:</h3>
        <ul class="text-sm text-yellow-700 space-y-1">
            <li>• Update status pengaduan secara berkala untuk transparansi</li>
            <li>• Gunakan prioritas untuk menentukan urutan penanganan</li>
            <li>• Berikan tanggapan melalui halaman detail untuk komunikasi dengan pelapor</li>
        </ul>
    </div>
</div>
@endsection
