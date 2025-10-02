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
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed'],
            'category' => ['required', 'in:pelajar,pegawai,pencari_kerja,pengusaha'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'category' => $request->category,
            'role' => 'masyarakat',
        ]);

        Auth::login($user);

        // Log user activity
        $user->activities()->create([
            'action' => 'register',
            'entity_type' => 'auth',
            'ip_address' => $request->ip(),
            'metadata' => json_encode([
                'user_agent' => $request->userAgent(),
                'timestamp' => now(),
            ]),
        ]);

        // Send welcome notification
        $user->notifications()->create([
            'title' => 'Selamat Datang di MANCE!',
            'message' => 'Terima kasih telah mendaftar di aplikasi MANCE. Nikmati berbagai layanan smart city Muara Enim.',
            'type' => 'welcome',
            'data' => json_encode(['registered_at' => now()]),
        ]);

        return redirect('/dashboard');
    }
}
