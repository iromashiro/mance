@extends('layouts.admin')

@section('title', 'Kelola Kategori')

@section('header', 'Kelola Kategori')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h1>
        <button onclick="openModal('add')"
            class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
            + Tambah Kategori
        </button>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">Slug: {{ $category->slug }}</p>
                </div>
                <div class="flex gap-2">
                    <button
                        onclick="openModal('edit', {{ $category->id }}, '{{ $category->name }}', '{{ $category->slug }}')"
                        class="text-yellow-600 hover:text-yellow-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="deleteCategory({{ $category->id }}, '{{ $category->name }}')"
                        class="text-red-600 hover:text-red-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Aplikasi:</span>
                    <span class="font-medium text-gray-900">{{ $category->applications->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Dibuat:</span>
                    <span class="font-medium text-gray-900">{{ $category->created_at->format('d M Y') }}</span>
                </div>
            </div>

            @if($category->applications->count() > 0)
            <div class="mt-4 pt-4 border-t">
                <p class="text-xs text-gray-500 mb-2">Aplikasi terkait:</p>
                <div class="flex flex-wrap gap-1">
                    @foreach($category->applications->take(3) as $app)
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                        {{ $app->name }}
                    </span>
                    @endforeach
                    @if($category->applications->count() > 3)
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded text-xs">
                        +{{ $category->applications->count() - 3 }} lagi
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                </path>
            </svg>
            <p class="mt-2 text-gray-500">Belum ada kategori</p>
        </div>
        @endforelse
    </div>
</div>

<!-- Modal Add/Edit -->
<div id="categoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex items-center justify-between mb-4">
            <h3 id="modalTitle" class="text-lg font-semibold">Tambah Kategori</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <form id="categoryForm" method="POST">
            @csrf
            <input type="hidden" id="methodField" name="_method" value="">

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">
                        Slug (Opsional)
                    </label>
                    <input type="text" name="slug" id="slug"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk generate otomatis</p>
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeModal()"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    let currentMode = 'add';
let currentId = null;

function openModal(mode, id = null, name = '', slug = '') {
    currentMode = mode;
    currentId = id;

    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('modalTitle');
    const methodField = document.getElementById('methodField');

    if (mode === 'add') {
        title.textContent = 'Tambah Kategori';
        form.action = '{{ route("admin.categories.store") }}';
        methodField.value = '';
        document.getElementById('name').value = '';
        document.getElementById('slug').value = '';
    } else {
        title.textContent = 'Edit Kategori';
        form.action = `/admin/categories/${id}`;
        methodField.value = 'PUT';
        document.getElementById('name').value = name;
        document.getElementById('slug').value = slug;
    }

    modal.classList.remove('hidden');
}

function closeModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function deleteCategory(id, name) {
    if (confirm(`Yakin ingin menghapus kategori "${name}"?\n\nPeringatan: Aplikasi yang terkait dengan kategori ini akan kehilangan kategorisasi.`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/categories/${id}`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function(e) {
    if (currentMode === 'add') {
        const slug = e.target.value
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        document.getElementById('slug').value = slug;
    }
});

// Close modal on outside click
document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endpush
@endsection
