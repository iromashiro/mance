<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display listing of users
     */
    public function index(Request $request)
    {
        $users = User::where('role', 'masyarakat')
            ->when($request->category, function ($query) use ($request) {
                return $query->where('category', $request->category);
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })
            ->withCount(['complaints', 'activities', 'favoriteApplications'])
            ->latest()
            ->paginate(20);

        // Get stats
        $stats = [
            'total' => User::where('role', 'masyarakat')->count(),
            'by_category' => User::where('role', 'masyarakat')
                ->select('category', DB::raw('count(*) as total'))
                ->groupBy('category')
                ->pluck('total', 'category')
                ->toArray(),
            'active_today' => UserActivity::whereDate('created_at', today())
                ->distinct('user_id')
                ->count('user_id'),
            'new_this_week' => User::where('role', 'masyarakat')
                ->where('created_at', '>=', now()->startOfWeek())
                ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Display user detail
     */
    public function show($id)
    {
        $user = User::where('role', 'masyarakat')->findOrFail($id);

        // Get user stats
        $stats = [
            'total_complaints' => $user->complaints()->count(),
            'pending_complaints' => $user->complaints()->where('status', 'pending')->count(),
            'resolved_complaints' => $user->complaints()->where('status', 'resolved')->count(),
            'total_activities' => $user->activities()->count(),
            'favorite_apps' => $user->favoriteApplications()->count(),
            'unread_notifications' => $user->notifications()->unread()->count(),
        ];

        // Get recent activities
        $recentActivities = $user->activities()
            ->latest('created_at')
            ->limit(20)
            ->get();

        // Get complaints
        $complaints = $user->complaints()
            ->with('category')
            ->latest()
            ->limit(10)
            ->get();

        // Get favorite applications
        $favoriteApps = $user->favoriteApplications()
            ->where('is_active', true)
            ->get();

        // Get notifications
        $notifications = $user->notifications()
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'stats',
            'recentActivities',
            'complaints',
            'favoriteApps',
            'notifications'
        ));
    }

    /**
     * Toggle user status (for blocking/unblocking)
     * Note: This would require adding an 'is_active' field to users table
     */
    public function toggleStatus($id)
    {
        $user = User::where('role', 'masyarakat')->findOrFail($id);

        // For now, we'll just log an activity
        // In production, you'd add an 'is_active' field to users table

        UserActivity::create([
            'user_id' => auth()->id(),
            'action' => 'toggle_user_status',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'ip_address' => request()->ip(),
            'metadata' => json_encode([
                'target_user' => $user->email,
                'timestamp' => now(),
            ]),
        ]);

        return back()->with('info', 'Fitur toggle status user akan segera tersedia.');
    }
}
