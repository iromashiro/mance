<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NewsController extends Controller
{
    /**
     * Display listing of news
     */
    public function index(Request $request)
    {
        $news = Cache::remember('news_page_' . $request->get('page', 1), 3600, function () use ($request) {
            return News::published()
                ->with('author')
                ->when($request->category, function ($query) use ($request) {
                    return $query->where('category', $request->category);
                })
                ->when($request->search, function ($query) use ($request) {
                    return $query->where(function ($q) use ($request) {
                        $q->where('title', 'like', '%' . $request->search . '%')
                            ->orWhere('excerpt', 'like', '%' . $request->search . '%');
                    });
                })
                ->latest('published_at')
                ->paginate(12);
        });

        // Get news categories
        $categories = News::published()
            ->distinct()
            ->pluck('category')
            ->filter()
            ->sort();

        // Get trending news (most viewed in last 7 days)
        $trendingNews = Cache::remember('trending_news', 3600, function () {
            return News::published()
                ->withCount(['views' => function ($query) {
                    $query->where('viewed_at', '>=', now()->subDays(7));
                }])
                ->orderBy('views_count', 'desc')
                ->limit(5)
                ->get();
        });

        return view('news.index', compact('news', 'categories', 'trendingNews'));
    }

    /**
     * Display news detail
     */
    public function show($slug)
    {
        $news = News::where('slug', $slug)
            ->published()
            ->with('author')
            ->firstOrFail();

        // Record view if user is logged in
        if (Auth::check()) {
            $existingView = NewsView::where('news_id', $news->id)
                ->where('user_id', Auth::id())
                ->whereDate('viewed_at', today())
                ->first();

            if (!$existingView) {
                NewsView::create([
                    'news_id' => $news->id,
                    'user_id' => Auth::id(),
                    'viewed_at' => now(),
                ]);
            }

            // Log activity
            Auth::user()->activities()->create([
                'action' => 'view_news',
                'entity_type' => 'news',
                'entity_id' => $news->id,
                'ip_address' => request()->ip(),
                'metadata' => json_encode([
                    'news_title' => $news->title,
                    'timestamp' => now(),
                ]),
            ]);
        }

        // Get related news
        $relatedNews = Cache::remember("related_news_{$news->id}", 3600, function () use ($news) {
            return News::published()
                ->where('id', '!=', $news->id)
                ->where(function ($query) use ($news) {
                    $query->where('category', $news->category);
                })
                ->latest('published_at')
                ->limit(4)
                ->get();
        });

        // Get view count
        $viewCount = $news->views()->count();

        return view('news.show', compact('news', 'relatedNews', 'viewCount'));
    }
}
