<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\MenuCategory;
use App\Models\MenuVariant;
use App\Models\MenuAddOn;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with(['category', 'variants', 'addOns']);

        // Filter by category
        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by availability
        if ($request->has('is_available')) {
            $query->where('is_available', $request->is_available === 'true');
        }

        $foods = $query->orderBy('display_order')->orderBy('name')->paginate(15);
        
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return Inertia::render('Admin/Food', [
            'foods' => $foods,
            'categories' => $categories,
            'filters' => [
                'category_id' => $request->category_id ?? 'all',
                'is_available' => $request->is_available ?? 'all',
            ],
        ]);
    }

    public function create()
    {
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return Inertia::render('Admin/Food/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:menu_categories,category_id',
            'availability_times' => 'nullable|array',
            'availability_times.*' => 'in:breakfast,lunch,dinner',
            'tags' => 'nullable|array',
            'calories' => 'nullable|integer|min:0',
            'protein' => 'nullable|integer|min:0',
            'carbs' => 'nullable|integer|min:0',
            'fat' => 'nullable|integer|min:0',
            'allergens' => 'nullable|array',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'preparation_time' => 'nullable|integer|min:0',
            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string',
            'variants.*.price_modifier' => 'required|numeric',
            'variants.*.size' => 'nullable|string',
            'variants.*.is_default' => 'boolean',
            'add_ons' => 'nullable|array',
            'add_ons.*.name' => 'required|string',
            'add_ons.*.price' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('food_pictures', 'public');
            $validated['picture'] = $path;
        }

        $validated['is_available'] = $request->has('is_available') ? $request->is_available : true;
        $validated['is_featured'] = $request->has('is_featured') ? $request->is_featured : false;

        // Extract variants and add-ons
        $variants = $validated['variants'] ?? [];
        $addOns = $validated['add_ons'] ?? [];
        unset($validated['variants'], $validated['add_ons']);

        $food = Food::create($validated);

        // Create variants
        foreach ($variants as $index => $variant) {
            MenuVariant::create([
                'food_id' => $food->id,
                'name' => $variant['name'],
                'price_modifier' => $variant['price_modifier'],
                'size' => $variant['size'] ?? null,
                'is_default' => $variant['is_default'] ?? false,
                'display_order' => $index,
            ]);
        }

        // Create add-ons
        foreach ($addOns as $index => $addOn) {
            MenuAddOn::create([
                'food_id' => $food->id,
                'name' => $addOn['name'],
                'price' => $addOn['price'],
                'display_order' => $index,
            ]);
        }

        return redirect()->route('admin.food')->with('success', 'Food item added successfully!');
    }

    public function edit($id)
    {
        $food = Food::with(['category', 'variants', 'addOns'])->findOrFail($id);
        $categories = MenuCategory::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return Inertia::render('Admin/Food/Edit', [
            'food' => $food,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, $id)
    {
        $food = Food::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|max:2048',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:menu_categories,category_id',
            'availability_times' => 'nullable|array',
            'availability_times.*' => 'in:breakfast,lunch,dinner',
            'tags' => 'nullable|array',
            'calories' => 'nullable|integer|min:0',
            'protein' => 'nullable|integer|min:0',
            'carbs' => 'nullable|integer|min:0',
            'fat' => 'nullable|integer|min:0',
            'allergens' => 'nullable|array',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer|min:0',
            'preparation_time' => 'nullable|integer|min:0',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:menu_variants,id',
            'variants.*.name' => 'required|string',
            'variants.*.price_modifier' => 'required|numeric',
            'variants.*.size' => 'nullable|string',
            'variants.*.is_default' => 'boolean',
            'add_ons' => 'nullable|array',
            'add_ons.*.id' => 'nullable|exists:menu_add_ons,id',
            'add_ons.*.name' => 'required|string',
            'add_ons.*.price' => 'required|numeric|min:0',
        ]);

        if ($request->hasFile('picture')) {
            // Delete old picture
            if ($food->picture && Storage::disk('public')->exists($food->picture)) {
                Storage::disk('public')->delete($food->picture);
            }
            $path = $request->file('picture')->store('food_pictures', 'public');
            $validated['picture'] = $path;
        } else {
            $validated['picture'] = $food->picture; // Preserve existing picture
        }

        $validated['is_available'] = $request->has('is_available') ? $request->is_available : true;
        $validated['is_featured'] = $request->has('is_featured') ? $request->is_featured : false;

        // Extract variants and add-ons
        $variants = $validated['variants'] ?? [];
        $addOns = $validated['add_ons'] ?? [];
        unset($validated['variants'], $validated['add_ons']);

        $food->update($validated);

        // Sync variants
        $existingVariantIds = [];
        foreach ($variants as $index => $variant) {
            if (isset($variant['id'])) {
                // Update existing variant
                MenuVariant::where('id', $variant['id'])
                    ->where('food_id', $food->id)
                    ->update([
                        'name' => $variant['name'],
                        'price_modifier' => $variant['price_modifier'],
                        'size' => $variant['size'] ?? null,
                        'is_default' => $variant['is_default'] ?? false,
                        'display_order' => $index,
                    ]);
                $existingVariantIds[] = $variant['id'];
            } else {
                // Create new variant
                $newVariant = MenuVariant::create([
                    'food_id' => $food->id,
                    'name' => $variant['name'],
                    'price_modifier' => $variant['price_modifier'],
                    'size' => $variant['size'] ?? null,
                    'is_default' => $variant['is_default'] ?? false,
                    'display_order' => $index,
                ]);
                $existingVariantIds[] = $newVariant->id;
            }
        }
        // Delete removed variants
        MenuVariant::where('food_id', $food->id)
            ->whereNotIn('id', $existingVariantIds)
            ->delete();

        // Sync add-ons
        $existingAddOnIds = [];
        foreach ($addOns as $index => $addOn) {
            if (isset($addOn['id'])) {
                // Update existing add-on
                MenuAddOn::where('id', $addOn['id'])
                    ->where('food_id', $food->id)
                    ->update([
                        'name' => $addOn['name'],
                        'price' => $addOn['price'],
                        'display_order' => $index,
                    ]);
                $existingAddOnIds[] = $addOn['id'];
            } else {
                // Create new add-on
                $newAddOn = MenuAddOn::create([
                    'food_id' => $food->id,
                    'name' => $addOn['name'],
                    'price' => $addOn['price'],
                    'display_order' => $index,
                ]);
                $existingAddOnIds[] = $newAddOn->id;
            }
        }
        // Delete removed add-ons
        MenuAddOn::where('food_id', $food->id)
            ->whereNotIn('id', $existingAddOnIds)
            ->delete();

        return redirect()->route('admin.food')->with('success', 'Food item updated successfully!');
    }

    public function destroy($id)
    {
        $food = Food::findOrFail($id);
        
        // Delete picture
        if ($food->picture && Storage::disk('public')->exists($food->picture)) {
            Storage::disk('public')->delete($food->picture);
        }

        $food->delete();

        return redirect()->route('admin.food')->with('success', 'Food item deleted successfully.');
    }
}
