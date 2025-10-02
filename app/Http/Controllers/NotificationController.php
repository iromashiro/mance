<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display listing of notifications
     */
    public function index(Request $request)
    {
        $query = Auth::user()->notifications();

        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->status === 'read') {
                $query->where('is_read', true);
            }
        }

        $notifications = $query->latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read and redirect to action URL
     */
    public function read(Notification $notification)
    {
        // Check if notification belongs to current user
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        // Mark as read
        $notification->update(['is_read' => true]);

        // Redirect to action URL if exists
        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Delete notification
     */
    public function destroy(Notification $notification)
    {
        // Check if notification belongs to current user
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    /**
     * Delete all read notifications
     */
    public function clearRead()
    {
        Auth::user()->notifications()
            ->where('is_read', true)
            ->delete();

        return back()->with('success', 'Notifikasi yang sudah dibaca berhasil dihapus.');
    }
}
