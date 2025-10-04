@extends('layouts.admin')

@section('title', 'Edit Aplikasi')

@section('header', 'Edit Aplikasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Aplikasi/Layanan</h1>
        <a href="{{ route('admin.applications.show', $application) }}"
            class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            ← Kembali ke Detail
        </a>
    </div>

    <form action="{{ route('admin.applications.update', $application) }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Aplikasi/Layanan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name', $application->name) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="description" rows="4" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('description', $application->description) }}</textarea>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- URL -->
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                    URL Aplikasi <span class="text-red-500">*</span>
                </label>
                <input type="url" name="url" id="url" required value="{{ old('url', $application->url) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                @error('url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Icon -->
            @if($application->icon_path)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Icon Saat Ini</label>
                <img src="{{ Storage::url($application->icon_path) }}" alt="{{ $application->name }}"
                    class="w-24 h-24 rounded-lg shadow-md object-cover">
            </div>
            @endif

            <!-- Icon Upload -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $application->icon_path ? 'Ganti Icon (Opsional)' : 'Upload Icon' }}
                </label>
                <input type="file" name="icon" id="icon" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="mt-1 text-sm text-gray-500">Format: JPG, JPEG, PNG, SVG. Maksimal 1MB</p>
                @error('icon')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div id="iconPreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview icon baru:</p>
                    <img src="" alt="Preview" class="w-24 h-24 rounded-lg shadow-md object-cover">
                </div>
            </div>

            <!-- Categories -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($categories as $category)
                    <label
                        class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, old('categories', $selectedCategories)) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-primary-600 shadow-sm">
                        <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
                @error('categories')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $application->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary-600 shadow-sm">
                    <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan aplikasi</span>
                </label>
            </div>

            <!-- Statistics -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 mb-2">Statistik</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Total Favorit:</span>
                        <span class="ml-2 font-medium">{{ $application->userFavorites->count() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Dibuat:</span>
                        <span class="ml-2 font-medium">{{ $application->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('admin.applications.show', $application) }}"
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

    <!-- Danger Zone -->
    <div class="bg-white shadow rounded-lg p-6 mt-6 border-2 border-red-200">
        <h3 class="text-lg font-semibold text-red-600 mb-4">Zona Bahaya</h3>
        <p class="text-sm text-gray-600 mb-4">
            Menghapus aplikasi ini akan menghapus semua data terkait. Tindakan ini tidak dapat dibatalkan.
        </p>
        <form action="{{ route('admin.applications.destroy', $application) }}" method="POST"
            onsubmit="return confirm('PERHATIAN! Yakin ingin menghapus aplikasi ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                Hapus Aplikasi Permanen
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('icon').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('iconPreview');
            const img = preview.querySelector('img');
            img.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection
