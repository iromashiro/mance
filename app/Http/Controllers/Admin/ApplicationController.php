<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Category;
use App\Models\AppCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ApplicationController extends Controller
{
    /**
     * Display listing of applications
     */
    public function index(Request $request)
    {
        $applications = Application::with('categories')
            ->when($request->search, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('is_active', $request->status === 'active');
            })
            ->orderBy('order_index')
            ->paginate(20);

        return view('admin.applications.index', compact('applications'));
    }

    /**
     * Show form for creating new application
     */
    public function create()
    {
        $categories = Category::all();
        $userCategories = ['pelajar', 'pegawai', 'pencari_kerja', 'pengusaha'];

        return view('admin.applications.create', compact('categories', 'userCategories'));
    }

    /**
     * Store new application
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:applications',
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:512',
            'url' => 'nullable|string|max:500',
            'type' => 'required|in:internal,external',
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'categories' => 'array',
            'user_categories' => 'array',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except(['icon', 'categories', 'user_categories']);

            // Handle icon upload
            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icons', 'public');
                $data['icon_path'] = '/storage/' . $iconPath;
            }

            // Generate slug if not provided
            if (empty($data['slug'])) {
                $data['slug'] = Str::slug($data['name']);
            }

            // Set default order_index
            if (empty($data['order_index'])) {
                $data['order_index'] = Application::max('order_index') + 1;
            }

            $application = Application::create($data);

            // Attach categories with user categories
            if ($request->categories && $request->user_categories) {
                foreach ($request->categories as $categoryId) {
                    foreach ($request->user_categories as $userCategory) {
                        AppCategory::create([
                            'application_id' => $application->id,
                            'category_id' => $categoryId,
                            'user_category' => $userCategory,
                        ]);
                    }
                }
            }

            // Clear cache
            Cache::forget('apps_home');
            Cache::flush(); // Clear all application-related cache

            DB::commit();

            return redirect()->route('admin.applications.index')
                ->with('success', 'Aplikasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show form for editing application
     */
    public function edit($id)
    {
        $application = Application::with('appCategories')->findOrFail($id);
        $categories = Category::all();
        $userCategories = ['pelajar', 'pegawai', 'pencari_kerja', 'pengusaha'];

        // Get selected categories and user categories
        $selectedCategories = $application->appCategories->pluck('category_id')->unique()->toArray();
        $selectedUserCategories = $application->appCategories->pluck('user_category')->unique()->toArray();

        return view('admin.applications.edit', compact(
            'application',
            'categories',
            'userCategories',
            'selectedCategories',
            'selectedUserCategories'
        ));
    }

    /**
     * Update application
     */
    public function update(Request $request, $id)
    {
        $application = Application::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:applications,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|image|mimes:svg,png,jpg,jpeg|max:512',
            'url' => 'nullable|string|max:500',
            'type' => 'required|in:internal,external',
            'is_active' => 'boolean',
            'order_index' => 'nullable|integer|min:0',
            'categories' => 'array',
            'user_categories' => 'array',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->except(['icon', 'categories', 'user_categories']);

            // Handle icon upload
            if ($request->hasFile('icon')) {
                // Delete old icon if exists
                if ($application->icon_path && Storage::disk('public')->exists(str_replace('/storage/', '', $application->icon_path))) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $application->icon_path));
                }

                $iconPath = $request->file('icon')->store('icons', 'public');
                $data['icon_path'] = '/storage/' . $iconPath;
            }

            $application->update($data);

            // Update categories
            AppCategory::where('application_id', $application->id)->delete();

            if ($request->categories && $request->user_categories) {
                foreach ($request->categories as $categoryId) {
                    foreach ($request->user_categories as $userCategory) {
                        AppCategory::create([
                            'application_id' => $application->id,
                            'category_id' => $categoryId,
                            'user_category' => $userCategory,
                        ]);
                    }
                }
            }

            // Clear cache
            Cache::forget('apps_home');
            Cache::flush();

            DB::commit();

            return redirect()->route('admin.applications.index')
                ->with('success', 'Aplikasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete application
     */
    public function destroy($id)
    {
        $application = Application::findOrFail($id);

        DB::beginTransaction();

        try {
            // Delete icon if exists
            if ($application->icon_path && Storage::disk('public')->exists(str_replace('/storage/', '', $application->icon_path))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $application->icon_path));
            }

            // Delete related records (app_categories will be deleted by cascade)
            $application->delete();

            // Clear cache
            Cache::forget('apps_home');
            Cache::flush();

            DB::commit();

            return redirect()->route('admin.applications.index')
                ->with('success', 'Aplikasi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
