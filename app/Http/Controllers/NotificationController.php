<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display listing of notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get notifications with pagination
        $notifications = $user->notifications()
            ->when($request->filter === 'unread', function ($query) {
                return $query->unread();
            })
            ->when($request->filter === 'read', function ($query) {
                return $query->read();
            })
            ->when($request->type, function ($query) use ($request) {
                return $query->where('type', $request->type);
            })
            ->latest()
            ->paginate(20);

        // Get unread count
        $unreadCount = $user->notifications()->unread()->count();

        // Get notification types for filter
        $types = $user->notifications()
            ->distinct()
            ->pluck('type')
            ->filter()
            ->sort();

        return view('notifications.index', compact('notifications', 'unreadCount', 'types'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah dibaca',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->unread()
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
