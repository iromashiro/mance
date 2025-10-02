<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display listing of news
     */
    public function index(Request $request)
    {
        $news = News::with('author')
            ->when($request->search, function ($query) use ($request) {
                return $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('content', 'like', '%' . $request->search . '%');
            })
            ->when($request->status, function ($query) use ($request) {
                if ($request->status === 'published') {
                    return $query->where('is_published', true);
                } else {
                    return $query->where('is_published', false);
                }
            })
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category', $request->category);
            })
            ->latest()
            ->paginate(20);

        // Get categories for filter
        $categories = News::distinct()->pluck('category')->filter()->sort();

        return view('admin.news.index', compact('news', 'categories'));
    }

    /**
     * Show form for creating new news
     */
    public function create()
    {
        $categories = ['Pendidikan', 'Kesehatan', 'Infrastruktur', 'Ekonomi', 'Event', 'Pengumuman', 'Pembangunan'];
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Store new news
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:500',
            'slug' => 'nullable|string|unique:news',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'required|string|max:50',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except('thumbnail');

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);

                // Ensure unique slug
                $count = 1;
                while (News::where('slug', $data['slug'])->exists()) {
                    $data['slug'] = Str::slug($data['title']) . '-' . $count;
                    $count++;
                }
            }

            // Generate excerpt if not provided
            if (empty($data['excerpt'])) {
                $data['excerpt'] = Str::limit(strip_tags($data['content']), 160);
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('news/thumbnails', 'public');
                $data['thumbnail_path'] = '/storage/' . $thumbnailPath;
            }

            // Set author
            $data['author_id'] = Auth::id();

            // Set published_at if publishing
            if ($request->is_published && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

            $news = News::create($data);

            // Clear cache
            Cache::forget('latest_news_dashboard');
            Cache::forget('news_page_1');

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'Berita berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display news detail
     */
    public function show($id)
    {
        $news = News::with(['author', 'views'])->findOrFail($id);
        $viewCount = $news->views()->count();

        return view('admin.news.show', compact('news', 'viewCount'));
    }

    /**
     * Show form for editing news
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = ['Pendidikan', 'Kesehatan', 'Infrastruktur', 'Ekonomi', 'Event', 'Pengumuman', 'Pembangunan'];

        return view('admin.news.edit', compact('news', 'categories'));
    }

    /**
     * Update news
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:500',
            'slug' => 'nullable|string|unique:news,slug,' . $id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'category' => 'required|string|max:50',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except('thumbnail');

            // Generate slug if changed
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['title']);

                // Ensure unique slug
                $count = 1;
                while (News::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                    $data['slug'] = Str::slug($data['title']) . '-' . $count;
                    $count++;
                }
            }

            // Generate excerpt if not provided
            if (empty($data['excerpt'])) {
                $data['excerpt'] = Str::limit(strip_tags($data['content']), 160);
            }

            // Handle thumbnail upload
            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($news->thumbnail_path && Storage::disk('public')->exists(str_replace('/storage/', '', $news->thumbnail_path))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $news->thumbnail_path));
                }

                $thumbnailPath = $request->file('thumbnail')->store('news/thumbnails', 'public');
                $data['thumbnail_path'] = '/storage/' . $thumbnailPath;
            }

            // Set published_at if publishing
            if ($request->is_published && !$news->is_published) {
                $data['published_at'] = $data['published_at'] ?? now();
            }

            $news->update($data);

            // Clear cache
            Cache::flush();

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'Berita berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete news
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete thumbnail if exists
            if ($news->thumbnail_path && Storage::disk('public')->exists(str_replace('/storage/', '', $news->thumbnail_path))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $news->thumbnail_path));
            }

            $news->delete();

            // Clear cache
            Cache::flush();

            DB::commit();

            return redirect()->route('admin.news.index')
                ->with('success', 'Berita berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Publish news
     */
    public function publish($id)
    {
        $news = News::findOrFail($id);

        $news->update([
            'is_published' => true,
            'published_at' => $news->published_at ?? now(),
        ]);

        Cache::flush();

        return back()->with('success', 'Berita berhasil dipublikasikan.');
    }

    /**
     * Unpublish news
     */
    public function unpublish($id)
    {
        $news = News::findOrFail($id);

        $news->update([
            'is_published' => false,
        ]);

        Cache::flush();

        return back()->with('success', 'Berita berhasil dibatalkan publikasi.');
    }
}
