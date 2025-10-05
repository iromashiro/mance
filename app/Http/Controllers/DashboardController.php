<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use App\Models\Application;
use App\Models\News;
use App\Models\Notification;
use App\Services\DesaApi;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index(DesaApi $desa)
    {
        $user = Auth::user();

        // Get statistics
        $totalComplaints = $user->complaints()->count();
        $activeComplaints = $user->complaints()->whereIn('status', ['pending', 'process'])->count();
        $completedComplaints = $user->complaints()->where('status', 'completed')->count();
        $unreadNotifications = $user->notifications()->whereNull('read_at')->count();

        // Get recent complaints
        $recentComplaints = $user->complaints()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // === News via external API first, fallback ke DB lokal ===
        $apiArticles = collect($desa->articles());

        if ($apiArticles->isNotEmpty()) {
            // Latest for dashboard (3 items)
            $recentNews = $apiArticles->take(3)->values();

            // Recommended (ambil 3 teratas juga - tanpa kategori user karena API tidak expose)
            $recommendedNews = $apiArticles->take(3)->values();

            // Latest general (5)
            $latestNews = $apiArticles->take(5)->values();
        } else {
            // Fallback ke lokal
            $recommendedNews = News::published()
                ->where('category', $user->category)
                ->latest('published_at')
                ->take(3)
                ->get();

            if ($recommendedNews->isEmpty()) {
                $recommendedNews = News::published()
                    ->latest('published_at')
                    ->take(3)
                    ->get();
            }

            $latestNews = News::published()
                ->latest('published_at')
                ->take(5)
                ->get();

            $recentNews = News::published()
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        // Get favorite applications
        $favoriteApps = $user->favorites()
            ->with('application')
            ->latest()
            ->take(4)
            ->get()
            ->pluck('application');

        // Get personalized app recommendations based on user category
        $recommendedApps = Application::where('is_active', true)
            ->whereHas('categories', function ($query) use ($user) {
                $query->where('slug', $user->category);
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        // If no apps for user category, get random apps instead
        if ($recommendedApps->isEmpty()) {
            $recommendedApps = Application::where('is_active', true)
                ->inRandomOrder()
                ->take(4)
                ->get();
        }

        return view('dashboard.index', compact(
            'totalComplaints',
            'activeComplaints',
            'completedComplaints',
            'unreadNotifications',
            'recentComplaints',
            'latestNews',
            'recommendedNews',
            'favoriteApps',
            'recommendedApps',
            'recentNews'
        ));
    }
}
