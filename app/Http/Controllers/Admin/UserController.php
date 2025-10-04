<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Display listing of users
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'masyarakat');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->whereNull('banned_at');
            } elseif ($request->status === 'banned') {
                $query->whereNotNull('banned_at');
            }
        }

        $users = $query->withCount(['complaints', 'activities', 'favorites'])
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display user details
     */
    public function show(User $user)
    {
        // Get user statistics
        $stats = [
            'total_complaints' => $user->complaints()->count(),
            'pending_complaints' => $user->complaints()->where('status', 'pending')->count(),
            'completed_complaints' => $user->complaints()->where('status', 'completed')->count(),
            'favorite_apps' => $user->favorites()->count(),
            'total_activities' => $user->activities()->count(),
        ];

        // Get recent complaints
        $recentComplaints = $user->complaints()
            ->with('category')
            ->latest()
            ->take(5)
            ->get();

        // Get recent activities
        $recentActivities = $user->activities()
            ->latest()
            ->take(10)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentComplaints', 'recentActivities'));
    }

    /**
     * Show edit form for user
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user details
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'category' => 'required|in:pelajar,pegawai,pencari_kerja,pengusaha',
            'is_active' => 'boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'category' => $request->category,
        ]);

        // Handle user ban/unban
        if (!$request->boolean('is_active')) {
            $user->update(['banned_at' => now()]);
        } else {
            $user->update(['banned_at' => null]);
        }

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        if ($user->banned_at) {
            $user->update(['banned_at' => null]);
            $message = 'Pengguna berhasil diaktifkan.';
        } else {
            $user->update(['banned_at' => now()]);
            $message = 'Pengguna berhasil dinonaktifkan.';
        }

        return back()->with('success', $message);
    }

    /**
     * Reset user password
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Send notification to user
        $user->notifications()->create([
            'title' => 'Password Direset',
            'message' => 'Password Anda telah direset oleh administrator. Password baru: ' . $request->password,
            'type' => 'account',
        ]);

        return back()->with('success', 'Password berhasil direset.');
    }

    /**
     * Delete user account
     */
    public function destroy(User $user)
    {
        // Don't allow deleting admin accounts
        if ($user->role === 'super_admin') {
            return back()->with('error', 'Tidak dapat menghapus akun admin.');
        }

        // Soft delete or hard delete based on your preference
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Export users to CSV
     */
    public function export(Request $request)
    {
        $filename = 'users_' . date('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $users = User::where('role', 'masyarakat')->get();

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Category', 'Created At']);

            // Data rows
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $user->category,
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
