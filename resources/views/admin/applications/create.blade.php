@extends('layouts.admin')

@section('title', 'Tambah Aplikasi')

@section('header', 'Tambah Aplikasi')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Aplikasi/Layanan Baru</h1>
        <a href="{{ route('admin.applications.index') }}"
            class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            ← Kembali ke Daftar Aplikasi
        </a>
    </div>

    <form action="{{ route('admin.applications.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded-lg p-6">
        @csrf

        <div class="space-y-6">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Aplikasi/Layanan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                    placeholder="Contoh: e-Planning, SIMPEG, dll"
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
                    placeholder="Jelaskan fungsi dan manfaat aplikasi ini..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('description') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">
                    Deskripsi singkat yang menjelaskan kegunaan aplikasi/layanan
                </p>
                @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- URL -->
            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 mb-2">
                    URL Aplikasi <span class="text-red-500">*</span>
                </label>
                <input type="url" name="url" id="url" required value="{{ old('url') }}"
                    placeholder="https://example.com"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="mt-1 text-sm text-gray-500">
                    Link lengkap ke aplikasi/layanan (harus diawali http:// atau https://)
                </p>
                @error('url')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Icon Upload -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                    Icon/Logo Aplikasi
                </label>
                <input type="file" name="icon" id="icon" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="mt-1 text-sm text-gray-500">
                    Format: JPG, JPEG, PNG, SVG. Maksimal 1MB. Ukuran rekomendasi: 512x512px
                </p>
                @error('icon')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- Preview -->
                <div id="iconPreview" class="mt-4 hidden">
                    <p class="text-sm text-gray-600 mb-2">Preview icon:</p>
                    <img src="" alt="Preview" class="w-24 h-24 rounded-lg shadow-md object-cover">
                </div>
            </div>

            <!-- Categories -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kategori
                </label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($categories as $category)
                    <label
                        class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                            {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    Pilih kategori yang sesuai untuk aplikasi ini (bisa lebih dari satu)
                </p>
                @error('categories')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Active Status -->
            <div>
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                    <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan aplikasi</span>
                </label>
                <p class="ml-6 mt-1 text-sm text-gray-500">
                    Jika dicentang, aplikasi akan ditampilkan ke pengguna. Jika tidak, aplikasi akan tersembunyi
                </p>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold mb-1">Tips:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Gunakan nama yang jelas dan mudah dikenali</li>
                            <li>Deskripsi sebaiknya singkat namun informatif</li>
                            <li>Pastikan URL aplikasi valid dan dapat diakses</li>
                            <li>Icon yang baik akan meningkatkan user experience</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('admin.applications.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                    Simpan Aplikasi
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Icon preview
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
