<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Log user activity
        if (Auth::user()) {
            Auth::user()->activities()->create([
                'action' => 'login',
                'entity_type' => 'auth',
                'ip_address' => $request->ip(),
                'metadata' => json_encode([
                    'user_agent' => $request->userAgent(),
                    'timestamp' => now(),
                ]),
            ]);
        }

        // Redirect based on role
        if (Auth::user()->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/dashboard');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        // Log user activity before logout
        if (Auth::user()) {
            Auth::user()->activities()->create([
                'action' => 'logout',
                'entity_type' => 'auth',
                'ip_address' => $request->ip(),
                'metadata' => json_encode([
                    'timestamp' => now(),
                ]),
            ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
