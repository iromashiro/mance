<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Complaint;
use App\Models\Application;
use App\Models\News;
use App\Models\Notification;

class DashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Get statistics
        $totalComplaints = $user->complaints()->count();
        $activeComplaints = $user->complaints()->whereIn('status', ['pending', 'process'])->count();
        $completedComplaints = $user->complaints()->where('status', 'completed')->count();
        $unreadNotifications = $user->notifications()->where('is_read', false)->count();

        // Get recent complaints
        $recentComplaints = $user->complaints()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Get latest news
        $latestNews = News::where('status', 'published')
            ->latest('published_at')
            ->take(5)
            ->get();

        // Get favorite applications
        $favoriteApps = $user->favorites()
            ->with('application')
            ->latest()
            ->take(4)
            ->get()
            ->pluck('application');

        // Get personalized app recommendations based on user category
        $recommendedApps = Application::where('status', 'active')
            ->whereHas('categories', function ($query) use ($user) {
                $query->where('slug', $user->category);
            })
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('dashboard.index', compact(
            'totalComplaints',
            'activeComplaints',
            'completedComplaints',
            'unreadNotifications',
            'recentComplaints',
            'latestNews',
            'favoriteApps',
            'recommendedApps'
        ));
    }
}
