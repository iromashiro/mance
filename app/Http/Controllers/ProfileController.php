<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display user profile
     */
    public function index()
    {
        $user = Auth::user();

        // Get user statistics
        $stats = [
            'total_complaints' => $user->complaints()->count(),
            'pending_complaints' => $user->complaints()->pending()->count(),
            'resolved_complaints' => $user->complaints()->resolved()->count(),
            'total_activities' => $user->activities()->count(),
            'favorite_apps' => $user->favoriteApplications()->count(),
            'unread_notifications' => $user->notifications()->unread()->count(),
        ];

        // Get recent activities
        $recentActivities = $user->activities()
            ->latest('created_at')
            ->limit(10)
            ->get();

        return view('profile.index', compact('user', 'stats', 'recentActivities'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'category' => ['required', 'in:pelajar,pegawai,pencari_kerja,pengusaha'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'category' => $request->category,
        ]);

        // Log activity
        $user->activities()->create([
            'action' => 'update_profile',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'ip_address' => $request->ip(),
            'metadata' => json_encode([
                'updated_fields' => array_keys($request->only(['name', 'email', 'category'])),
                'timestamp' => now(),
            ]),
        ]);

        // Create notification
        $user->notifications()->create([
            'title' => 'Profil Berhasil Diperbarui',
            'message' => 'Informasi profil Anda telah berhasil diperbarui.',
            'type' => 'profile',
            'data' => json_encode([
                'updated_at' => now(),
            ]),
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Log activity
        $user->activities()->create([
            'action' => 'update_password',
            'entity_type' => 'user',
            'entity_id' => $user->id,
            'ip_address' => $request->ip(),
            'metadata' => json_encode([
                'timestamp' => now(),
            ]),
        ]);

        // Create notification
        $user->notifications()->create([
            'title' => 'Password Berhasil Diubah',
            'message' => 'Password Anda telah berhasil diubah. Jika Anda tidak melakukan perubahan ini, segera hubungi administrator.',
            'type' => 'security',
            'data' => json_encode([
                'updated_at' => now(),
                'ip_address' => $request->ip(),
            ]),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
