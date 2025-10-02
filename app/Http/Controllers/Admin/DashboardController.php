<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\News;
use App\Models\Complaint;
use App\Models\Notification;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_users' => User::where('role', 'masyarakat')->count(),
            'users_by_category' => User::where('role', 'masyarakat')
                ->select('category', DB::raw('count(*) as total'))
                ->groupBy('category')
                ->pluck('total', 'category')
                ->toArray(),
            'total_applications' => Application::count(),
            'active_applications' => Application::where('is_active', true)->count(),
            'total_news' => News::count(),
            'published_news' => News::where('is_published', true)->count(),
            'total_complaints' => Complaint::count(),
            'pending_complaints' => Complaint::where('status', 'pending')->count(),
            'resolved_complaints' => Complaint::where('status', 'resolved')->count(),
        ];

        // Recent activities
        $recentActivities = UserActivity::with('user')
            ->latest('created_at')
            ->limit(10)
            ->get();

        // Recent complaints
        $recentComplaints = Complaint::with(['user', 'category'])
            ->latest()
            ->limit(5)
            ->get();

        // Popular applications
        $popularApps = Application::withCount('userFavorites')
            ->orderBy('user_favorites_count', 'desc')
            ->limit(5)
            ->get();

        // Complaint stats by category
        $complaintsByCategory = Complaint::join('complaint_categories', 'complaints.category_id', '=', 'complaint_categories.id')
            ->select('complaint_categories.name', DB::raw('count(complaints.id) as total'))
            ->groupBy('complaint_categories.id', 'complaint_categories.name')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'recentComplaints',
            'popularApps',
            'complaintsByCategory'
        ));
    }

    /**
     * Display analytics page
     */
    public function analytics()
    {
        // User registration trend (last 30 days)
        $userTrend = User::where('role', 'masyarakat')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Complaint trend (last 30 days)
        $complaintTrend = Complaint::where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Activity heatmap
        $activityHeatmap = UserActivity::where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('EXTRACT(HOUR FROM created_at) as hour'),
                DB::raw('EXTRACT(DOW FROM created_at) as day'),
                DB::raw('count(*) as total')
            )
            ->groupBy('hour', 'day')
            ->get();

        // News performance
        $newsPerformance = News::withCount('views')
            ->published()
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // Response time analysis
        $responseTime = DB::table('complaints')
            ->select(
                DB::raw('AVG(EXTRACT(EPOCH FROM (resolved_at - created_at))/3600) as avg_hours'),
                DB::raw('MIN(EXTRACT(EPOCH FROM (resolved_at - created_at))/3600) as min_hours'),
                DB::raw('MAX(EXTRACT(EPOCH FROM (resolved_at - created_at))/3600) as max_hours')
            )
            ->where('status', 'resolved')
            ->whereNotNull('resolved_at')
            ->first();

        return view('admin.analytics', compact(
            'userTrend',
            'complaintTrend',
            'activityHeatmap',
            'newsPerformance',
            'responseTime'
        ));
    }

    /**
     * Show broadcast notification form
     */
    public function broadcastForm()
    {
        $userCategories = ['pelajar', 'pegawai', 'pencari_kerja', 'pengusaha'];
        return view('admin.notifications.broadcast', compact('userCategories'));
    }

    /**
     * Send broadcast notification
     */
    public function sendBroadcast(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,success,danger',
            'target' => 'required|in:all,category',
            'category' => 'required_if:target,category|in:pelajar,pegawai,pencari_kerja,pengusaha',
        ]);

        $query = User::where('role', 'masyarakat');

        if ($request->target === 'category') {
            $query->where('category', $request->category);
        }

        $users = $query->get();
        $notificationData = [];
        $now = now();

        foreach ($users as $user) {
            $notificationData[] = [
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'data' => json_encode([
                    'broadcast' => true,
                    'sent_by' => auth()->user()->name,
                    'sent_at' => $now,
                ]),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Insert in chunks for better performance
        foreach (array_chunk($notificationData, 500) as $chunk) {
            Notification::insert($chunk);
        }

        return redirect()->route('admin.dashboard')
            ->with('success', 'Notifikasi berhasil dikirim ke ' . count($users) . ' pengguna.');
    }
}
