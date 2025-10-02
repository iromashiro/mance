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
        $query = News::where('status', 'published')
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

        // Category filter (if news has categories)
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        $news = $query->latest('published_at')
            ->paginate(9);

        return view('news.index', compact('news'));
    }

    /**
     * Display news details
     */
    public function show(News $news)
    {
        // Check if news is published
        if ($news->status !== 'published' && (!Auth::check() || !Auth::user()->isAdmin())) {
            abort(404);
        }

        // Track view
        $this->trackView($news);

        // Get related news
        $relatedNews = News::where('status', 'published')
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
        // Check if user already viewed this news today
        if (Auth::check()) {
            $existingView = NewsView::where('news_id', $news->id)
                ->where('user_id', Auth::id())
                ->whereDate('created_at', today())
                ->first();

            if (!$existingView) {
                NewsView::create([
                    'news_id' => $news->id,
                    'user_id' => Auth::id(),
                    'ip_address' => request()->ip(),
                ]);
            }
        } else {
            // Track by IP for guests
            $existingView = NewsView::where('news_id', $news->id)
                ->where('ip_address', request()->ip())
                ->whereDate('created_at', today())
                ->first();

            if (!$existingView) {
                NewsView::create([
                    'news_id' => $news->id,
                    'user_id' => null,
                    'ip_address' => request()->ip(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
