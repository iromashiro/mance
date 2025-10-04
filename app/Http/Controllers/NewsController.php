<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display listing of news
     */
    public function index(Request $request)
    {
        $query = News::published()
            ->with('author');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $news = $query->latest('published_at')->paginate(9);

        return view('news.index', compact('news'));
    }

    /**
     * Display news details
     */
    public function show(News $news)
    {
        // Allow unpublished only for admin; otherwise must be published (is_published + published_at <= now)
        $isPublished = $news->is_published && ! is_null($news->published_at) && $news->published_at->lte(now());
        $isAdmin = Auth::check() && Auth::user()->isAdmin();

        if (! $isPublished && ! $isAdmin) {
            abort(404);
        }

        // Track view
        $this->trackView($news);

        // Get related news
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('news.show', compact('news', 'relatedNews'));
    }

    /**
     * Track news view
     */
    public function trackView(News $news)
    {
        // Only track for authenticated users (routes are already behind auth)
        if (! Auth::check()) {
            return response()->json(['success' => true]);
        }

        $existingView = NewsView::where('news_id', $news->id)
            ->where('user_id', Auth::id())
            ->whereDate('viewed_at', today())
            ->first();

        if (! $existingView) {
            NewsView::create([
                'news_id' => $news->id,
                'user_id' => Auth::id(),
                'viewed_at' => now(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
