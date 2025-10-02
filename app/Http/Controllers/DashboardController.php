<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\News;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Get recommended applications based on user category
        $recommendedApps = Cache::remember("apps_for_{$user->category}", 3600, function () use ($user) {
            return Application::active()
                ->whereHas('appCategories', function ($query) use ($user) {
                    $query->where('user_category', $user->category);
                })
                ->orderBy('order_index')
                ->limit(8)
                ->get();
        });

        // Get user's favorite applications
        $favoriteApps = $user->favoriteApplications()
            ->where('is_active', true)
            ->orderBy('order_index')
            ->limit(4)
            ->get();

        // Get latest news
        $latestNews = Cache::remember('latest_news_dashboard', 3600, function () {
            return News::published()
                ->latest('published_at')
                ->limit(4)
                ->get();
        });

        // Get user's recent complaints
        $recentComplaints = $user->complaints()
            ->with('category')
            ->latest()
            ->limit(3)
            ->get();

        // Get unread notifications count
        $unreadNotifications = $user->notifications()
            ->unread()
            ->count();

        // Dashboard stats
        $stats = [
            'total_complaints' => $user->complaints()->count(),
            'pending_complaints' => $user->complaints()->pending()->count(),
            'resolved_complaints' => $user->complaints()->resolved()->count(),
            'unread_notifications' => $unreadNotifications,
        ];

        // Log user activity
        $user->activities()->create([
            'action' => 'view_dashboard',
            'entity_type' => 'dashboard',
            'ip_address' => request()->ip(),
            'metadata' => json_encode([
                'timestamp' => now(),
            ]),
        ]);

        return view('dashboard.index', compact(
            'user',
            'recommendedApps',
            'favoriteApps',
            'latestNews',
            'recentComplaints',
            'stats'
        ));
    }
}
