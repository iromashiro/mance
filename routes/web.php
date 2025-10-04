<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ApplicationController as AdminApplicationController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

// Public routes
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/onboarding', fn() => view('auth.onboarding'))->name('onboarding');
    // Password Reset Routes (stub for now - will redirect to login)
    Route::get('/forgot-password', function () {
        return redirect()->route('login')->with('info', 'Silakan hubungi admin untuk reset password.');
    })->name('password.request');

    Route::get('/reset-password', function () {
        return redirect()->route('login');
    })->name('password.reset');
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Applications/Services
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{application}/favorite', [ApplicationController::class, 'toggleFavorite'])->name('applications.favorite');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

    // Complaints (Pengaduan)
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/vote', [ComplaintController::class, 'vote'])->name('complaints.vote');
    Route::post('/complaints/{complaint}/response', [ComplaintController::class, 'addResponse'])->name('complaints.response');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Applications Management
    Route::resource('applications', AdminApplicationController::class);

    // News Management
    Route::resource('news', AdminNewsController::class);
    Route::post('news/{news}/publish', [AdminNewsController::class, 'publish'])->name('news.publish');
    Route::post('news/{news}/unpublish', [AdminNewsController::class, 'unpublish'])->name('news.unpublish');

    // Complaints Management
    Route::get('complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('complaints/{complaint}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::get('complaints/{complaint}/edit', [AdminComplaintController::class, 'edit'])->name('complaints.edit');
    Route::put('complaints/{complaint}', [AdminComplaintController::class, 'update'])->name('complaints.update');
    Route::post('complaints/{complaint}/respond', [AdminComplaintController::class, 'respond'])->name('complaints.respond');

    // Users Management
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [AdminUserController::class, 'update'])->name('users.update');

    // Categories Management
    Route::get('categories', [AdminDashboardController::class, 'categories'])->name('categories.index');

    // Reports & Settings
    Route::get('reports', [AdminDashboardController::class, 'reports'])->name('reports');
    Route::get('settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::get('profile', [AdminDashboardController::class, 'profile'])->name('profile');
});

// API Routes for AJAX/Tracking
Route::prefix('api')->middleware('auth')->group(function () {
    Route::post('/applications/{application}/track', [ApplicationController::class, 'track']);
    Route::post('/news/{news}/view', [NewsController::class, 'trackView']);
});

// PWA routes
Route::get('/manifest.json', function () {
    return response()->file(public_path('manifest.json'));
});

Route::get('/service-worker.js', function () {
    return response()->file(public_path('service-worker.js'))
        ->header('Content-Type', 'application/javascript');
});

Route::get('/offline', function () {
    return response()->file(public_path('offline.html'));
})->name('offline');
