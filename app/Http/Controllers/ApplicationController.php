<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Category;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ApplicationController extends Controller
{
    /**
     * Display listing of applications/services
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get applications based on filters
        $applications = Application::active()
            ->with('categories')
            ->when($request->category, function ($query) use ($request) {
                return $query->whereHas('categories', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->orderBy('order_index')
            ->paginate(12);

        // Get categories for filter
        $categories = Category::all();

        // Get recommended apps for user category
        $recommendedApps = Cache::remember("recommended_apps_{$user->category}", 3600, function () use ($user) {
            return Application::active()
                ->whereHas('appCategories', function ($query) use ($user) {
                    $query->where('user_category', $user->category);
                })
                ->orderBy('order_index')
                ->limit(4)
                ->get();
        });

        // Get user's favorite app IDs
        $favoriteIds = $user->favoriteApplications()->pluck('application_id')->toArray();

        return view('applications.index', compact(
            'applications',
            'categories',
            'recommendedApps',
            'favoriteIds'
        ));
    }

    /**
     * Display application detail
     */
    public function show($slug)
    {
        $application = Application::where('slug', $slug)
            ->active()
            ->with('categories')
            ->firstOrFail();

        // Check if favorited by user
        $isFavorited = Auth::user()->favoriteApplications()
            ->where('application_id', $application->id)
            ->exists();

        // Log activity
        Auth::user()->activities()->create([
            'action' => 'view_application',
            'entity_type' => 'application',
            'entity_id' => $application->id,
            'ip_address' => request()->ip(),
            'metadata' => json_encode([
                'application_name' => $application->name,
                'timestamp' => now(),
            ]),
        ]);

        // Get similar applications
        $similarApps = Cache::remember("similar_apps_{$application->id}", 3600, function () use ($application) {
            $categoryIds = $application->categories->pluck('id');

            return Application::active()
                ->where('id', '!=', $application->id)
                ->whereHas('categories', function ($query) use ($categoryIds) {
                    $query->whereIn('category_id', $categoryIds);
                })
                ->orderBy('order_index')
                ->limit(4)
                ->get();
        });

        // If external, redirect to external URL
        if ($application->type === 'external' && $application->url) {
            // Log before redirect
            Auth::user()->activities()->create([
                'action' => 'redirect_external',
                'entity_type' => 'application',
                'entity_id' => $application->id,
                'ip_address' => request()->ip(),
                'metadata' => json_encode([
                    'external_url' => $application->url,
                    'timestamp' => now(),
                ]),
            ]);

            return redirect()->away($application->url);
        }

        return view('applications.show', compact('application', 'isFavorited', 'similarApps'));
    }

    /**
     * Toggle favorite status for application
     */
    public function toggleFavorite($id)
    {
        $application = Application::active()->findOrFail($id);
        $user = Auth::user();

        $favorite = UserFavorite::where('user_id', $user->id)
            ->where('application_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $message = 'Aplikasi dihapus dari favorit';
            $isFavorited = false;
        } else {
            UserFavorite::create([
                'user_id' => $user->id,
                'application_id' => $id,
            ]);
            $message = 'Aplikasi ditambahkan ke favorit';
            $isFavorited = true;
        }

        // Log activity
        $user->activities()->create([
            'action' => $isFavorited ? 'add_favorite' : 'remove_favorite',
            'entity_type' => 'application',
            'entity_id' => $id,
            'ip_address' => request()->ip(),
            'metadata' => json_encode([
                'application_name' => $application->name,
            ]),
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_favorited' => $isFavorited,
        ]);
    }
}
