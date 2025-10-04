<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'min:8', 'confirmed'],
            'category' => ['required', 'in:pelajar,pegawai,pencari_kerja,pengusaha'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'category' => $validated['category'],
            'role' => 'masyarakat',
        ]);

        Auth::login($user);

        // Log user activity
        $user->activities()->create([
            'action' => 'register',
            'entity_type' => 'auth',
            'metadata' => [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toIso8601String(),
            ],
        ]);

        // Send welcome notification
        $user->notifications()->create([
            'title' => 'Selamat Datang di MANCE!',
            'message' => 'Terima kasih telah mendaftar di aplikasi MANCE. Nikmati berbagai layanan smart city Muara Enim.',
            'type' => 'welcome',
            'data' => [
                'registered_at' => now()->toIso8601String(),
            ],
        ]);

        return redirect()->route('dashboard')->with('status', 'Pendaftaran berhasil, selamat datang di MANCE!');
    }
}
