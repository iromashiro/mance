@extends('layouts.admin')

@section('title', 'Buat Berita Baru')
@section('header', 'Buat Berita Baru')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tambah Berita Baru</h1>
        <a href="{{ route('admin.news.index') }}" class="text-primary-600 hover:text-primary-800 text-sm font-medium">
            ← Kembali ke Daftar Berita
        </a>
    </div>

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow rounded-lg p-6">
        @csrf

        <div class="space-y-6">
            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Judul Berita <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" id="title" required value="{{ old('title') }}"
                    placeholder="Masukkan judul berita yang menarik..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Slug (opsional, auto dari judul bila kosong) --}}
            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                    Slug (Opsional)
                </label>
                <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                    placeholder="Akan otomatis dibuat dari judul jika kosong"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="mt-1 text-sm text-gray-500">
                    Biarkan kosong untuk generate otomatis dari judul
                </p>
                @error('slug')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Excerpt --}}
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                    Ringkasan <span class="text-red-500">*</span>
                </label>
                <textarea name="excerpt" id="excerpt" rows="3" required
                    placeholder="Ringkasan singkat berita (maksimal 500 karakter)"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">{{ old('excerpt') }}</textarea>
                <p class="mt-1 text-sm text-gray-500">
                    Ringkasan akan ditampilkan di daftar berita (maks. 500 karakter)
                </p>
                @error('excerpt')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konten Berita (Flowbite WYSIWYG / TipTap) --}}
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                    Konten Berita <span class="text-red-500">*</span>
                </label>

                {{-- Wrapper editor --}}
                <div class="w-full border border-gray-200 rounded-lg bg-gray-50">

                    {{-- Toolbar --}}
                    <div class="px-3 py-2 border-b border-gray-200">
                        <div class="flex flex-wrap items-center">
                            <div class="flex items-center space-x-1 flex-wrap">
                                <!-- Basic -->
                                <button id="toggleBoldButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Bold">B</button>
                                <button id="toggleItalicButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Italic"><span class="italic">I</span></button>
                                <button id="toggleUnderlineButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Underline"><span class="underline">U</span></button>
                                <button id="toggleStrikeButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Strikethrough">S</button>

                                <div class="px-1"><span class="block w-px h-4 bg-gray-300"></span></div>

                                <!-- Heading dropdown -->
                                <button id="typographyDropdownButton" data-dropdown-toggle="typographyDropdown"
                                    class="rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-600 hover:bg-gray-200"
                                    type="button">
                                    Format
                                    <svg class="inline -me-0.5 ms-1.5 h-3.5 w-3.5" viewBox="0 0 24 24" fill="none">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m19 9-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="typographyDropdown" class="z-10 hidden w-56 rounded-sm bg-white p-2 shadow-sm">
                                    <ul class="space-y-1 text-sm font-medium">
                                        <li><button id="toggleParagraphButton"
                                                class="w-full text-left px-3 py-2 rounded-sm hover:bg-gray-100">Paragraph</button>
                                        </li>
                                        <li><button data-heading-level="1"
                                                class="w-full text-left px-3 py-2 rounded-sm hover:bg-gray-100">Heading
                                                1</button>
                                        </li>
                                        <li><button data-heading-level="2"
                                                class="w-full text-left px-3 py-2 rounded-sm hover:bg-gray-100">Heading
                                                2</button>
                                        </li>
                                        <li><button data-heading-level="3"
                                                class="w-full text-left px-3 py-2 rounded-sm hover:bg-gray-100">Heading
                                                3</button>
                                        </li>
                                    </ul>
                                </div>

                                <div class="px-1"><span class="block w-px h-4 bg-gray-300"></span></div>

                                <!-- Lists & quote -->
                                <button id="toggleListButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Bullet List">• • •</button>
                                <button id="toggleOrderedListButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Ordered List">1.</button>
                                <button id="toggleBlockquoteButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Blockquote">❝</button>
                                <button id="toggleHRButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Horizontal Rule">—</button>

                                <div class="px-1"><span class="block w-px h-4 bg-gray-300"></span></div>

                                <!-- Align -->
                                <button id="toggleLeftAlignButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Align Left">⟸</button>
                                <button id="toggleCenterAlignButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Align Center">↔</button>
                                <button id="toggleRightAlignButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Align Right">⟹</button>

                                <div class="px-1"><span class="block w-px h-4 bg-gray-300"></span></div>

                                <!-- Link / media -->
                                <button id="toggleLinkButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Insert Link">🔗</button>
                                <button id="removeLinkButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Remove Link">⛔</button>
                                <button id="addImageButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Insert Image">🖼️</button>
                                <button id="addVideoButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Insert YouTube">▶️</button>

                                <div class="px-1"><span class="block w-px h-4 bg-gray-300"></span></div>

                                <!-- Highlight / Code -->
                                <button id="toggleHighlightButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Highlight">🖍️</button>
                                <button id="toggleCodeButton" type="button"
                                    class="p-1.5 text-gray-500 rounded-sm hover:text-gray-900 hover:bg-gray-100"
                                    title="Inline Code">{</button>
                            </div>
                        </div>
                    </div>

                    {{-- Area editor --}}
                    <div class="px-4 py-3 bg-white rounded-b-lg">
                        <div id="wysiwyg-example" class="min-h-[320px] text-sm text-gray-800 focus:outline-none"></div>
                    </div>
                </div>

                {{-- Hidden field untuk submit ke server --}}
                <textarea id="content" name="content" class="hidden">{{ old('content') }}</textarea>

                @error('content')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Konten akan tersimpan sebagai HTML di field <code>content</code>.
                </p>
            </div>

            {{-- Gambar Utama --}}
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                    Gambar/Foto Berita
                </label>
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                <p class="mt-1 text-sm text-gray-500">
                    Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB
                </p>
                @error('image')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                {{-- Preview --}}
                <div id="imagePreview" class="mt-4 hidden">
                    <img src="" alt="Preview" class="max-w-md rounded-lg shadow-md">
                </div>
            </div>

            {{-- Pengaturan Publikasi --}}
            <div class="border-t pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Publikasi</h3>
                <div class="space-y-4">
                    {{-- Published Status --}}
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_published" value="1"
                                {{ old('is_published', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <span class="ml-2 text-sm font-medium text-gray-700">Publikasikan sekarang</span>
                        </label>
                        <p class="ml-6 mt-1 text-sm text-gray-500">
                            Jika dicentang, berita akan langsung terbit. Jika tidak, akan tersimpan sebagai draft
                        </p>
                    </div>

                    {{-- Published Date (Opsional) --}}
                    <div>
                        <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Publikasi (Opsional)
                        </label>
                        <input type="datetime-local" name="published_at" id="published_at"
                            value="{{ old('published_at') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <p class="mt-1 text-sm text-gray-500">
                            Biarkan kosong untuk menggunakan waktu sekarang
                        </p>
                        @error('published_at')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-6 border-t">
                <a href="{{ route('admin.news.index') }}"
                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <div class="flex gap-2">
                    <button type="submit" name="action" value="draft"
                        class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Simpan Draft
                    </button>
                    <button type="submit" name="action" value="publish"
                        class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
                        Publikasikan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.5.2/flowbite.min.js"></script>

{{-- TipTap via ESM (tanpa build) --}}
<script type="module">
    import { Editor }     from 'https://esm.sh/@tiptap/core@2.6.6';
    import StarterKit     from 'https://esm.sh/@tiptap/starter-kit@2.6.6';
    import Highlight      from 'https://esm.sh/@tiptap/extension-highlight@2.6.6';
    import Underline      from 'https://esm.sh/@tiptap/extension-underline@2.6.6';
    import Link           from 'https://esm.sh/@tiptap/extension-link@2.6.6';
    import TextAlign      from 'https://esm.sh/@tiptap/extension-text-align@2.6.6';
    import Image          from 'https://esm.sh/@tiptap/extension-image@2.6.6';
    import YouTube        from 'https://esm.sh/@tiptap/extension-youtube@2.6.6';
    import TextStyle      from 'https://esm.sh/@tiptap/extension-text-style@2.6.6';
    import FontFamily     from 'https://esm.sh/@tiptap/extension-font-family@2.6.6';
    import { Color }      from 'https://esm.sh/@tiptap/extension-color@2.6.6';

    // Ambil initial content dari server (old('content'))
    const initialContent = @json(old('content', '<p></p>'));

    const editor = new Editor({
      element: document.querySelector('#wysiwyg-example'),
      content: initialContent,
      extensions: [
        StarterKit,
        TextStyle, Color, FontFamily,
        Highlight, Underline,
        Link.configure({ openOnClick: false, autolink: true, defaultProtocol: 'https' }),
        TextAlign.configure({ types: ['heading','paragraph'] }),
        Image,
        YouTube,
      ],
      editorProps: {
        attributes: {
          class: 'prose prose-sm max-w-none focus:outline-none',
        },
      },
      onUpdate: ({ editor }) => {
        document.getElementById('content').value = editor.getHTML();
      },
      onCreate: ({ editor }) => {
        document.getElementById('content').value = editor.getHTML();
      }
    });

    // Toolbar actions
    const $ = (id) => document.getElementById(id);
    $('toggleBoldButton')     .addEventListener('click', () => editor.chain().focus().toggleBold().run());
    $('toggleItalicButton')   .addEventListener('click', () => editor.chain().focus().toggleItalic().run());
    $('toggleUnderlineButton').addEventListener('click', () => editor.chain().focus().toggleUnderline().run());
    $('toggleStrikeButton')   .addEventListener('click', () => editor.chain().focus().toggleStrike().run());
    $('toggleHighlightButton').addEventListener('click', () => {
      const isOn = editor.isActive('highlight');
      editor.chain().focus().toggleHighlight({ color: isOn ? undefined : '#fff59a' }).run();
    });
    $('toggleCodeButton')     .addEventListener('click', () => editor.chain().focus().toggleCode().run());

    // Link & media
    $('toggleLinkButton').addEventListener('click', () => {
      const url = prompt('URL (https://...)', 'https://');
      if (url) editor.chain().focus().toggleLink({ href: url }).run();
    });
    $('removeLinkButton').addEventListener('click', () => editor.chain().focus().unsetLink().run());
    $('addImageButton').addEventListener('click', () => {
      const url = prompt('URL gambar', 'https://placehold.co/800x450');
      if (url) editor.chain().focus().setImage({ src: url, alt: '' }).run();
    });
    $('addVideoButton').addEventListener('click', () => {
      const url = prompt('URL YouTube', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');
      if (url) editor.commands.setYoutubeVideo({ src: url, width: 640, height: 360 });
    });

    // Lists / block
    $('toggleListButton')       .addEventListener('click', () => editor.chain().focus().toggleBulletList().run());
    $('toggleOrderedListButton').addEventListener('click', () => editor.chain().focus().toggleOrderedList().run());
    $('toggleBlockquoteButton') .addEventListener('click', () => editor.chain().focus().toggleBlockquote().run());
    $('toggleHRButton')         .addEventListener('click', () => editor.chain().focus().setHorizontalRule().run());

    // Align
    $('toggleLeftAlignButton')  .addEventListener('click', () => editor.chain().focus().setTextAlign('left').run());
    $('toggleCenterAlignButton').addEventListener('click', () => editor.chain().focus().setTextAlign('center').run());
    $('toggleRightAlignButton') .addEventListener('click', () => editor.chain().focus().setTextAlign('right').run());

    // Heading dropdown (Flowbite)
    const typographyDropdown = window.FlowbiteInstances.getInstance('Dropdown', 'typographyDropdown');
    $('toggleParagraphButton').addEventListener('click', () => {
      editor.chain().focus().setParagraph().run(); typographyDropdown.hide();
    });
    document.querySelectorAll('[data-heading-level]').forEach(btn => {
      btn.addEventListener('click', () => {
        editor.chain().focus().toggleHeading({ level: parseInt(btn.dataset.headingLevel) }).run();
        typographyDropdown.hide();
      });
    });

    // Pastikan konten terakhir ikut saat submit
    const form = document.getElementById('content')?.form;
    form?.addEventListener('submit', () => {
      document.getElementById('content').value = editor.getHTML();
    });
</script>
@endpush
@endsection
