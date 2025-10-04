<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display listing of news
     */
    public function index(Request $request)
    {
        $query = News::with('author');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('is_published') && $request->is_published !== '') {
            $query->where('is_published', $request->is_published);
        }

        $news = $query->latest()->paginate(20);

        return view('admin.news.index', compact('news'));
    }

    /**
     * Show form for creating new news
     */
    public function create()
    {
        return view('admin.news.create');
    }

    /**
     * Store new news
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:news,slug',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only(['title', 'excerpt', 'content']);
        $data['is_published'] = $request->boolean('is_published');

        // Generate slug if not provided
        $data['slug'] = $request->slug ?: Str::slug($request->title);

        // Set author
        $data['author'] = Auth::user()->name;

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('news', 'public');
            $data['image_url'] = $path;
        }

        // Set published date
        if ($request->boolean('is_published')) {
            $data['published_at'] = $request->published_at ?: now();
        }

        News::create($data);

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dibuat.');
    }

    /**
     * Display news details
     */
    public function show(News $news)
    {
        $news->load('views');

        return view('admin.news.show', compact('news'));
    }

    /**
     * Show form for editing news
     */
    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update news
     */
    public function update(Request $request, News $news)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:news,slug,' . $news->id,
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_published' => 'required|boolean',
            'published_at' => 'nullable|date',
        ]);

        $data = $request->only(['title', 'excerpt', 'content']);
        $data['is_published'] = $request->boolean('is_published');

        // Update slug if provided
        if ($request->slug) {
            $data['slug'] = $request->slug;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image_url) {
                Storage::disk('public')->delete($news->image_url);
            }

            $path = $request->file('image')->store('news', 'public');
            $data['image_url'] = $path;
        }

        // Update published date
        if ($request->boolean('is_published') && !$news->published_at) {
            $data['published_at'] = $request->published_at ?: now();
        }

        $news->update($data);

        return redirect()->route('admin.news.show', $news)
            ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Delete news
     */
    public function destroy(News $news)
    {
        // Delete image if exists
        if ($news->image_url) {
            Storage::disk('public')->delete($news->image_url);
        }

        $news->delete();

        return redirect()->route('admin.news.index')
            ->with('success', 'Berita berhasil dihapus.');
    }

    /**
     * Publish news
     */
    public function publish(News $news)
    {
        $news->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return back()->with('success', 'Berita berhasil dipublikasikan.');
    }

    /**
     * Unpublish news
     */
    public function unpublish(News $news)
    {
        $news->update([
            'is_published' => false,
        ]);

        return back()->with('success', 'Berita berhasil di-unpublish.');
    }
}
