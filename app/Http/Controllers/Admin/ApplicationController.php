<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Category;
use App\Models\AppCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Display listing of applications
     */
    public function index(Request $request)
    {
        $query = Application::with('categories');

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->withCount('favorites')
            ->latest()
            ->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Show form for creating new application
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.applications.create', compact('categories'));
    }

    /**
     * Store new application
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|url',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:1024',
            'status' => 'required|in:active,inactive',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->only(['name', 'description', 'url', 'status']);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('applications/icons', 'public');
            $data['icon_url'] = $path;
        }

        $application = Application::create($data);

        // Attach categories
        if ($request->has('categories')) {
            foreach ($request->categories as $categoryId) {
                AppCategory::create([
                    'application_id' => $application->id,
                    'category_id' => $categoryId,
                ]);
            }
        }

        return redirect()->route('admin.applications.index')
            ->with('success', 'Aplikasi berhasil ditambahkan.');
    }

    /**
     * Display application details
     */
    public function show(Application $application)
    {
        $application->load(['categories', 'favorites.user']);

        // Get usage statistics
        $stats = [
            'total_favorites' => $application->favorites()->count(),
            'views_count' => $application->views_count ?? 0,
            'clicks_count' => $application->clicks_count ?? 0,
        ];

        return view('admin.applications.show', compact('application', 'stats'));
    }

    /**
     * Show form for editing application
     */
    public function edit(Application $application)
    {
        $categories = Category::all();
        $selectedCategories = $application->categories->pluck('id')->toArray();

        return view('admin.applications.edit', compact('application', 'categories', 'selectedCategories'));
    }

    /**
     * Update application
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'url' => 'required|url',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:1024',
            'status' => 'required|in:active,inactive',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->only(['name', 'description', 'url', 'status']);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($application->icon_url) {
                Storage::disk('public')->delete($application->icon_url);
            }

            $path = $request->file('icon')->store('applications/icons', 'public');
            $data['icon_url'] = $path;
        }

        $application->update($data);

        // Sync categories
        if ($request->has('categories')) {
            // Remove existing categories
            AppCategory::where('application_id', $application->id)->delete();

            // Add new categories
            foreach ($request->categories as $categoryId) {
                AppCategory::create([
                    'application_id' => $application->id,
                    'category_id' => $categoryId,
                ]);
            }
        }

        return redirect()->route('admin.applications.show', $application)
            ->with('success', 'Aplikasi berhasil diperbarui.');
    }

    /**
     * Delete application
     */
    public function destroy(Application $application)
    {
        // Delete icon if exists
        if ($application->icon_url) {
            Storage::disk('public')->delete($application->icon_url);
        }

        // Delete related records
        AppCategory::where('application_id', $application->id)->delete();

        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Aplikasi berhasil dihapus.');
    }

    /**
     * Toggle application status
     */
    public function toggleStatus(Application $application)
    {
        $newStatus = $application->status === 'active' ? 'inactive' : 'active';
        $application->update(['status' => $newStatus]);

        return back()->with('success', 'Status aplikasi berhasil diubah.');
    }

    /**
     * Reset application statistics
     */
    public function resetStats(Application $application)
    {
        $application->update([
            'views_count' => 0,
            'clicks_count' => 0,
        ]);

        return back()->with('success', 'Statistik aplikasi berhasil direset.');
    }
}
