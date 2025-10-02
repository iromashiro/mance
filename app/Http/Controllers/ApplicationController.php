<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    /**
     * Display listing of applications
     */
    public function index(Request $request)
    {
        $query = Application::where('status', 'active')
            ->with(['categories']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(12);
        $categories = Category::all();

        return view('applications.index', compact('applications', 'categories'));
    }

    /**
     * Display application details
     */
    public function show(Application $application)
    {
        // Track view
        $application->increment('views_count');

        // Log activity
        if (Auth::check()) {
            Auth::user()->activities()->create([
                'activity_type' => 'view_application',
                'description' => 'Melihat layanan: ' . $application->name,
            ]);
        }

        // Get related applications
        $relatedApps = Application::where('status', 'active')
            ->where('id', '!=', $application->id)
            ->whereHas('categories', function ($query) use ($application) {
                $categoryIds = $application->categories->pluck('id');
                $query->whereIn('categories.id', $categoryIds);
            })
            ->take(4)
            ->get();

        return view('applications.show', compact('application', 'relatedApps'));
    }

    /**
     * Toggle favorite status
     */
    public function toggleFavorite(Request $request, Application $application)
    {
        $user = Auth::user();

        $existingFavorite = UserFavorite::where('user_id', $user->id)
            ->where('application_id', $application->id)
            ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
            $message = 'Dihapus dari favorit';
        } else {
            UserFavorite::create([
                'user_id' => $user->id,
                'application_id' => $application->id,
            ]);
            $message = 'Ditambahkan ke favorit';
        }

        if ($request->ajax()) {
            return response()->json(['message' => $message]);
        }

        return back()->with('success', $message);
    }

    /**
     * Track application click/usage
     */
    public function track(Request $request, Application $application)
    {
        // Increment click count
        $application->increment('clicks_count');

        // Log activity if user is logged in
        if (Auth::check()) {
            Auth::user()->activities()->create([
                'activity_type' => 'use_application',
                'description' => 'Menggunakan layanan: ' . $application->name,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
