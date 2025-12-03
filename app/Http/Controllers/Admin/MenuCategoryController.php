<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = MenuCategory::withCount('foodItems')
            ->orderBy('display_order')
            ->orderBy('category_name')
            ->get();

        return Inertia::render('Admin/MenuCategories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new category
     */
    public function create()
    {
        return Inertia::render('Admin/MenuCategories/Create');
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:menu_categories,category_name',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'icon' => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('category_images', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? $request->is_active : true;
        $validated['display_order'] = $validated['display_order'] ?? MenuCategory::max('display_order') + 1;

        MenuCategory::create($validated);

        return redirect()->route('admin.menu-categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified category
     */
    public function edit($id)
    {
        $category = MenuCategory::findOrFail($id);
        
        return Inertia::render('Admin/MenuCategories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $category = MenuCategory::findOrFail($id);

        $validated = $request->validate([
            'category_name' => 'required|string|max:255|unique:menu_categories,category_name,' . $id . ',category_id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'icon' => 'nullable|string|max:10',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $path = $request->file('image')->store('category_images', 'public');
            $validated['image'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? $request->is_active : true;

        $category->update($validated);

        return redirect()->route('admin.menu-categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $category = MenuCategory::findOrFail($id);

        // Check if category has food items
        if ($category->foodItems()->count() > 0) {
            return redirect()->route('admin.menu-categories.index')
                ->with('error', 'Cannot delete category. It has food items assigned.');
        }

        // Delete image
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.menu-categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Update display order (for drag-and-drop)
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.category_id' => 'required|exists:menu_categories,category_id',
            'categories.*.display_order' => 'required|integer',
        ]);

        foreach ($request->categories as $categoryData) {
            MenuCategory::where('category_id', $categoryData['category_id'])
                ->update(['display_order' => $categoryData['display_order']]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }
}
