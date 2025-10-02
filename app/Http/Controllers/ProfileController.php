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
            'completed_complaints' => $user->complaints()->where('status', 'completed')->count(),
            'favorite_apps' => $user->favorites()->count(),
            'total_activities' => $user->activities()->count(),
        ];

        // Get recent activities
        $recentActivities = $user->activities()
            ->latest()
            ->take(10)
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'category' => 'required|in:pelajar,pegawai,pencaker,wirausaha,umum',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'category' => $request->category,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }

    /**
     * Upload profile photo (optional)
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Delete old photo if exists
        if ($user->profile_photo_path) {
            \Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->update([
            'profile_photo_path' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
