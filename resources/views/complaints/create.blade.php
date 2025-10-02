@extends('layouts.app')

@section('title', 'Buat Pengaduan')

@section('content')
<div class="px-4 py-6 sm:px-6 lg:px-8 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center">
            <a href="{{ route('complaints.index') }}" class="mr-4">
                <svg class="h-6 w-6 text-gray-500 hover:text-gray-700" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Buat Pengaduan Baru</h1>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm text-blue-700">
                    Pastikan pengaduan Anda jelas dan lengkap. Sertakan foto atau dokumen pendukung jika diperlukan.
                    Anda akan mendapatkan nomor tiket untuk tracking pengaduan.
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('complaints.store') }}" method="POST" enctype="multipart/form-data" x-data="complaintForm()">
        @csrf

        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6 space-y-6">

                <!-- Kategori -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">
                        Kategori Pengaduan <span class="text-red-500">*</span>
                    </label>
                    <select id="category_id" name="category_id" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('category_id') border-red-300 @enderror">
                        <option value="">Pilih kategori</option>
                        @foreach(\App\Models\ComplaintCategory::all() as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Judul Pengaduan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" required value="{{ old('title') }}"
                        placeholder="Tuliskan judul singkat pengaduan Anda"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-300 @enderror">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700">
                        Lokasi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="location" id="location" required value="{{ old('location') }}"
                        placeholder="Contoh: Jl. Sudirman No. 123, Kec. Muara Enim"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('location') border-red-300 @enderror">
                    @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Deskripsi Pengaduan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="description" name="description" rows="6" required
                        placeholder="Jelaskan detail pengaduan Anda..."
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Minimal 20 karakter</p>
                </div>

                <!-- Priority -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tingkat Prioritas <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-2">
                        <label class="flex items-start cursor-pointer">
                            <input type="radio" name="priority" value="low" required
                                {{ old('priority', 'low') == 'low' ? 'checked' : '' }}
                                class="mt-1 text-primary-600 focus:ring-primary-500 border-gray-300">
                            <span class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Rendah</span>
                                <span class="block text-sm text-gray-500">Tidak urgent, bisa ditangani dalam waktu
                                    normal</span>
                            </span>
                        </label>
                        <label class="flex items-start cursor-pointer">
                            <input type="radio" name="priority" value="medium" required
                                {{ old('priority') == 'medium' ? 'checked' : '' }}
                                class="mt-1 text-primary-600 focus:ring-primary-500 border-gray-300">
                            <span class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Sedang</span>
                                <span class="block text-sm text-gray-500">Perlu ditangani segera</span>
                            </span>
                        </label>
                        <label class="flex items-start cursor-pointer">
                            <input type="radio" name="priority" value="high" required
                                {{ old('priority') == 'high' ? 'checked' : '' }}
                                class="mt-1 text-primary-600 focus:ring-primary-500 border-gray-300">
                            <span class="ml-3">
                                <span class="block text-sm font-medium text-gray-900">Tinggi</span>
                                <span class="block text-sm text-gray-500">Urgent dan membutuhkan penanganan cepat</span>
                            </span>
                        </label>
                    </div>
                    @error('priority')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Images -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Foto/Bukti
                    </label>
                    <div
                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="images"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                    <span>Upload file</span>
                                    <input id="images" name="images[]" type="file" multiple accept="image/*"
                                        class="sr-only" @change="handleFiles">
                                </label>
                                <p class="pl-1">atau drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF maksimal 2MB per file</p>
                        </div>
                    </div>
                    @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Image Preview -->
                    <div x-show="selectedFiles.length > 0" class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">File yang akan diupload:</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <template x-for="(file, index) in selectedFiles" :key="index">
                                <div class="relative">
                                    <img :src="file.url" class="h-24 w-full object-cover rounded-md">
                                    <button type="button" @click="removeFile(index)"
                                        class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Privacy -->
                <div>
                    <label class="flex items-start">
                        <input type="checkbox" name="is_private" value="1" {{ old('is_private') ? 'checked' : '' }}
                            class="mt-1 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <span class="ml-3">
                            <span class="block text-sm font-medium text-gray-900">Jadikan pengaduan ini privat</span>
                            <span class="block text-sm text-gray-500">Hanya Anda dan admin yang dapat melihat pengaduan
                                ini</span>
                        </span>
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                <a href="{{ route('complaints.index') }}"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Batal
                </a>
                <button type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Kirim Pengaduan
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function complaintForm() {
    return {
        selectedFiles: [],

        handleFiles(event) {
            const files = Array.from(event.target.files);
            files.forEach(file => {
                if (file.size <= 2 * 1024 * 1024) { // Max 2MB
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedFiles.push({
                            file: file,
                            url: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('File ' + file.name + ' terlalu besar. Maksimal 2MB.');
                }
            });
        },

        removeFile(index) {
            this.selectedFiles.splice(index, 1);
            // Reset input if no files left
            if (this.selectedFiles.length === 0) {
                document.getElementById('images').value = '';
            }
        }
    }
}
</script>
@endpush

@endsection