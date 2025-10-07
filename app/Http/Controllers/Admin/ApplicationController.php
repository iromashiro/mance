<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppCategory;
use App\Models\Application;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Generate unique slug from application name.
     *
     * Slug dibuat permanen (tidak berubah saat update).
     *
     * @param string $name
     * @param int|null $excludeId
     * @return string
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name);
        $slug = $base !== '' ? $base : Str::random(8);
        $original = $slug;
        $i = 2;

        while (
            Application::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = "{$original}-{$i}";
            $i++;
        }

        return $slug;
    }
    /**
     * Display listing of applications
     */
    public function index(Request $request)
    {
        $query = Application::with('categories');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status filter (map ?status=active|inactive to is_active boolean)
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $applications = $query->withCount('userFavorites')
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
            'is_active' => 'nullable|boolean',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->only(['name', 'description', 'url']);
        $data['slug'] = $this->generateUniqueSlug($request->name);
        $data['is_active'] = $request->boolean('is_active', true);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('applications/icons', 'public');
            $data['icon_path'] = $path;
        }

        $application = Application::create($data);

        // Attach categories
        if ($request->filled('categories')) {
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
        $application->load(['categories', 'userFavorites.user']);

        // Get usage statistics (safe default if columns absent)
        $stats = [
            'total_favorites' => $application->userFavorites()->count(),
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
            'is_active' => 'nullable|boolean',
            'categories' => 'array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->only(['name', 'description', 'url']);
        $data['is_active'] = $request->boolean('is_active', $application->is_active);

        // Handle icon upload
        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($application->icon_path) {
                Storage::disk('public')->delete($application->icon_path);
            }

            $path = $request->file('icon')->store('applications/icons', 'public');
            $data['icon_path'] = $path;
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
        if ($application->icon_path) {
            Storage::disk('public')->delete($application->icon_path);
        }

        // Delete related records
        AppCategory::where('application_id', $application->id)->delete();

        $application->delete();

        return redirect()->route('admin.applications.index')
            ->with('success', 'Aplikasi berhasil dihapus.');
    }

    /**
     * Toggle application active flag
     */
    public function toggleStatus(Application $application)
    {
        $application->update(['is_active' => ! $application->is_active]);

        return back()->with('success', 'Status aplikasi berhasil diubah.');
    }

    /**
     * Reset application statistics (guard columns)
     */
    public function resetStats(Application $application)
    {
        $toUpdate = [];
        if (Schema::hasColumn('applications', 'views_count')) {
            $toUpdate['views_count'] = 0;
        }
        if (Schema::hasColumn('applications', 'clicks_count')) {
            $toUpdate['clicks_count'] = 0;
        }

        if (! empty($toUpdate)) {
            $application->update($toUpdate);
        }

        return back()->with('success', 'Statistik aplikasi berhasil direset.');
    }
}
