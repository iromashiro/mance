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
    return view('welcome');
})->name('home');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Applications/Services
    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{slug}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::post('/applications/{id}/favorite', [ApplicationController::class, 'toggleFavorite'])->name('applications.favorite');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('news.index');
    Route::get('/news/{slug}', [NewsController::class, 'show'])->name('news.show');

    // Complaints (Pengaduan)
    Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{ticket_number}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{id}/vote', [ComplaintController::class, 'vote'])->name('complaints.vote');
    Route::post('/complaints/{id}/rate', [ComplaintController::class, 'rate'])->name('complaints.rate');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
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
    Route::post('news/{id}/publish', [AdminNewsController::class, 'publish'])->name('news.publish');
    Route::post('news/{id}/unpublish', [AdminNewsController::class, 'unpublish'])->name('news.unpublish');

    // Complaints Management
    Route::get('complaints', [AdminComplaintController::class, 'index'])->name('complaints.index');
    Route::get('complaints/{id}', [AdminComplaintController::class, 'show'])->name('complaints.show');
    Route::post('complaints/{id}/update-status', [AdminComplaintController::class, 'updateStatus'])->name('complaints.update-status');
    Route::post('complaints/{id}/respond', [AdminComplaintController::class, 'respond'])->name('complaints.respond');

    // Users Management
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{id}', [AdminUserController::class, 'show'])->name('users.show');
    Route::post('users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Analytics
    Route::get('analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');

    // Broadcast Notification
    Route::get('notifications/broadcast', [AdminDashboardController::class, 'broadcastForm'])->name('notifications.broadcast');
    Route::post('notifications/broadcast', [AdminDashboardController::class, 'sendBroadcast'])->name('notifications.send-broadcast');
});

// PWA routes
Route::get('/manifest.json', function () {
    return response()->file(public_path('manifest.json'));
});

Route::get('/service-worker.js', function () {
    return response()->file(public_path('service-worker.js'));
});

Route::get('/offline', function () {
    return view('offline');
})->name('offline');
