<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\NewsView;
use App\Services\DesaApi;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class NewsController extends Controller
{
    /**
     * Display listing of news (User side).
     * Prefer external Desa API; fallback ke database lokal jika API kosong/error.
     */
    public function index(Request $request, DesaApi $desa)
    {
        // 1) Coba ambil dari API eksternal
        $external = collect($desa->articles());

        if ($external->isNotEmpty()) {
            // Pencarian sederhana pada judul/ekscerpt/konten
            if ($request->filled('search')) {
                $q = mb_strtolower($request->string('search'));
                $external = $external->filter(function ($a) use ($q) {
                    $hay = mb_strtolower(($a->title ?? '') . ' ' . ($a->excerpt ?? '') . ' ' . strip_tags($a->content ?? ''));
                    return str_contains($hay, $q);
                });
            }

            // (Opsional) filter category id jika tersedia
            if ($request->filled('category')) {
                $cat = (string) $request->get('category');
                $external = $external->filter(fn($a) => (string) ($a->category ?? '') === $cat);
            }

            // Trending (top 5 views dalam 7 hari) — persist 7 hari
            $trendingNews = Cache::remember('news_trending_external', now()->addDays(7), function () use ($external) {
                $sevenDays = now()->subDays(7);
                return $external
                    ->filter(function ($a) use ($sevenDays) {
                        $pub = $a->published_at ?? null;
                        return $pub ? Carbon::parse($pub)->gte($sevenDays) : false;
                    })
                    ->sortByDesc(fn($a) => (int) ($a->views ?? 0))
                    ->take(5)
                    ->values();
            });

            // Featured (terbanyak views dalam 3 hari terakhir)
            $featuredNews = $external
                ->filter(function ($a) {
                    $pub = $a->published_at ?? null;
                    return $pub ? Carbon::parse($pub)->gte(now()->subDays(3)) : false;
                })
                ->sortByDesc(fn($a) => (int) ($a->views ?? 0))
                ->first();

            if (! $featuredNews) {
                $featuredNews = $external->first();
            }

            // Pagination manual untuk daftar berita
            $perPage = 9;
            $page = (int) $request->integer('page', 1);
            $total = $external->count();
            $items = $external->slice(($page - 1) * $perPage, $perPage)->values();

            $news = new LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => URL::current(), 'query' => $request->query()]
            );

            return view('news.index', compact('news', 'trendingNews', 'featuredNews'));
        }

        // 2) Fallback ke DB lokal
        $query = News::published()->with('author');

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Trending lokal: top 5 views dalam 7 hari, cache 7 hari
        $trendingNews = Cache::remember('news_trending_local', now()->addDays(7), function () {
            $ids = NewsView::where('viewed_at', '>=', now()->subDays(7))
                ->selectRaw('news_id, count(*) as views_count')
                ->groupBy('news_id')
                ->orderByDesc('views_count')
                ->limit(5)
                ->pluck('news_id')
                ->all();

            $collection = News::published()->whereIn('id', $ids)->get()->keyBy('id');
            return collect($ids)->map(fn($id) => $collection->get($id))->filter()->values();
        });

        // Featured lokal: terbanyak view dalam 3 hari
        $featuredId = NewsView::where('viewed_at', '>=', now()->subDays(3))
            ->selectRaw('news_id, count(*) as vc')
            ->groupBy('news_id')
            ->orderByDesc('vc')
            ->value('news_id');

        $featuredNews = $featuredId
            ? News::published()->find($featuredId)
            : News::published()->latest('published_at')->first();

        $news = $query->latest('published_at')->paginate(9);

        return view('news.index', compact('news', 'trendingNews', 'featuredNews'));
    }

    /**
     * Show detail berita.
     * Jika id ada di API eksternal, gunakan itu; jika tidak, fallback ke DB lokal.
     */
    public function show($id, DesaApi $desa)
    {
        // Coba ambil dari API eksternal
        $external = $desa->article((int) $id);

        if ($external) {
            // Related dari API (ambil 3 selain current)
            $relatedNews = collect($desa->articles())
                ->filter(fn($a) => (string) $a->id !== (string) $id)
                ->take(3)
                ->values();

            // Jangan track view lokal untuk artikel eksternal
            return view('news.show', [
                'news' => $external,
                'relatedNews' => $relatedNews,
                'isExternal' => true,
            ]);
        }

        // Fallback ke lokal
        $news = News::findOrFail($id);

        // Hanya publik untuk non-admin
        $isPublished = $news->is_published && !is_null($news->published_at) && $news->published_at->lte(now());
        $isAdmin = Auth::check() && Auth::user()->isAdmin();

        if (! $isPublished && ! $isAdmin) {
            abort(404);
        }

        // Track view lokal
        $this->trackView($news);

        // Related lokal
        $relatedNews = News::published()
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('news.show', [
            'news' => $news,
            'relatedNews' => $relatedNews,
            'isExternal' => false,
        ]);
    }

    /**
     * Track news view (hanya untuk berita lokal).
     */
    public function trackView(News $news)
    {
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
