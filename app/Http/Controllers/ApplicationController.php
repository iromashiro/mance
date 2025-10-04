<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ApplicationController extends Controller
{
    /**
     * Display listing of applications
     */
    public function index(Request $request)
    {
        $query = Application::where('is_active', true)
            ->with(['categories']);

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(12);
        $categories = Category::all();

        if ($request->boolean('embed')) {
            return view('applications.embed', compact('applications', 'categories'));
        }

        return view('applications.index', compact('applications', 'categories'));
    }

    /**
     * Display application details
     */
    public function show(Application $application)
    {
        // Track view count if column exists
        if (Schema::hasColumn('applications', 'views_count')) {
            $application->increment('views_count');
        }

        // Log activity
        if (Auth::check()) {
            Auth::user()->activities()->create([
                'action' => 'view_application',
                'entity_type' => 'application',
                'entity_id' => $application->id,
                'metadata' => [
                    'name' => $application->name,
                ],
                'ip_address' => request()->ip(),
            ]);
        }

        // Get related applications
        $relatedApps = Application::where('is_active', true)
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
        // Increment click count if column exists
        if (Schema::hasColumn('applications', 'clicks_count')) {
            $application->increment('clicks_count');
        }

        // Log activity if user is logged in
        if (Auth::check()) {
            Auth::user()->activities()->create([
                'action' => 'use_application',
                'entity_type' => 'application',
                'entity_id' => $application->id,
                'metadata' => [
                    'name' => $application->name,
                ],
                'ip_address' => $request->ip(),
            ]);
        }

        return response()->json(['success' => true]);
    }
}
