<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Complaint;
use App\Models\Application;
use App\Models\News;
use App\Models\Category;
use App\Models\ComplaintCategory;
use App\Models\Notification;
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
        $statistics = [
            'total_users' => User::where('role', 'masyarakat')->count(),
            'total_complaints' => Complaint::count(),
            'pending_complaints' => Complaint::where('status', 'pending')->count(),
            'process_complaints' => Complaint::where('status', 'process')->count(),
            'completed_complaints' => Complaint::where('status', 'completed')->count(),
            'rejected_complaints' => Complaint::where('status', 'rejected')->count(),
            'active_applications' => Application::where('is_active', true)->count(),
            'published_news' => News::published()->count(),
        ];

        // Get recent complaints
        $recentComplaints = Complaint::with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        // Get user categories distribution
        $userCategories = User::where('role', 'masyarakat')
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        // Get monthly complaint statistics for chart
        $monthlyComplaints = Complaint::selectRaw('DATE_TRUNC(\'month\', created_at) as month, count(*) as count')
            ->whereRaw('created_at >= NOW() - INTERVAL \'6 months\'')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard', compact(
            'statistics',
            'recentComplaints',
            'userCategories',
            'monthlyComplaints'
        ));
    }

    /**
     * Categories management page
     */
    public function categories()
    {
        $categories = Category::all();
        $complaintCategories = ComplaintCategory::withCount('complaints')->get();

        return view('admin.categories.index', compact('categories', 'complaintCategories'));
    }

    /**
     * Reports page
     */
    public function reports()
    {
        // Get date range from request or default to last 30 days
        $startDate = request('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));

        // Complaint reports
        $complaintReport = Complaint::selectRaw('status, count(*) as count')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // User registration report
        $userReport = User::where('role', 'masyarakat')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // News view report (only published news)
        $newsReport = News::withCount(['views' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('viewed_at', [$startDate, $endDate]);
        }])
            ->published()
            ->orderBy('views_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'complaintReport',
            'userReport',
            'newsReport',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Settings page
     */
    public function settings()
    {
        // Get application settings (could be from database or config)
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'maintenance_mode' => app()->isDownForMaintenance(),
            'registration_enabled' => true, // You can store this in database
            'complaint_auto_close_days' => 30, // Days before auto-closing complaints
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Admin profile page
     */
    public function profile()
    {
        $admin = auth()->user();

        return view('admin.profile.index', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        // Check if this is a password change request
        if ($request->input('action') === 'change_password') {
            $validated = $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            // Verify current password
            if (!password_verify($validated['current_password'], $admin->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini salah']);
            }

            // Update password
            $admin->update([
                'password' => bcrypt($validated['new_password'])
            ]);

            return back()->with('success', 'Password berhasil diubah');
        }

        // Regular profile update
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $admin->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Analytics page
     */
    public function analytics()
    {
        // User analytics
        $userAnalytics = [
            'total' => User::where('role', 'masyarakat')->count(),
            'new_today' => User::where('role', 'masyarakat')->whereDate('created_at', today())->count(),
            'new_this_week' => User::where('role', 'masyarakat')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_this_month' => User::where('role', 'masyarakat')->whereMonth('created_at', now()->month)->count(),
        ];

        // Complaint analytics
        $complaintAnalytics = [
            'total' => Complaint::count(),
            'avg_resolution_time' => Complaint::where('status', 'completed')
                ->selectRaw('AVG(EXTRACT(EPOCH FROM (completed_at - created_at))/3600) as avg_hours')
                ->value('avg_hours'),
            'satisfaction_rate' => Complaint::where('status', 'completed')
                ->whereNotNull('satisfaction_rating')
                ->avg('satisfaction_rating'),
        ];

        // Popular services
        $popularServices = Application::where('is_active', true)
            ->withCount('favorites')
            ->orderBy('favorites_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'userAnalytics',
            'complaintAnalytics',
            'popularServices'
        ));
    }
}
