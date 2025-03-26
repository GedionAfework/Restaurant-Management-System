<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    public function index(Request $request)
    {
        $foods = Food::paginate(10); // Queries 'food' table
        return Inertia::render('Admin/Food', [
            'foods' => $foods->toArray(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Food/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|max:2048',
            'price' => 'required|numeric',
        ]);

        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('food_pictures', 'public');
            $validated['picture'] = $path;
        }

        $food = Food::create($validated);
        \Log::info('Food created:', $food->toArray());

        return redirect()->route('admin.food')->with('success', 'Food added successfully!');
    }

    public function edit($id)
    {
        $food = Food::findOrFail($id);
        \Log::info('Food data sent to frontend:', $food->toArray());
        return Inertia::render('Admin/Food/Edit', [
            'food' => $food->toArray(),
        ]);
    }
// ... (other methods unchanged)

public function update(Request $request, $id)
{
    \Log::info('Update request data:', [
        'all' => $request->all(),
        'files' => $request->hasFile('picture') ? $request->file('picture')->getClientOriginalName() : 'No file uploaded',
    ]);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'type' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'picture' => 'nullable|image|max:2048',
        'price' => 'required|numeric',
    ]);

    $food = Food::findOrFail($id);

    if ($request->hasFile('picture')) {
        if ($food->picture && \Storage::disk('public')->exists($food->picture)) {
            \Storage::disk('public')->delete($food->picture);
        }
        $path = $request->file('picture')->store('food_pictures', 'public');
        $validated['picture'] = $path;
    } else {
        $validated['picture'] = $food->picture; // Preserve existing picture
    }

    $food->update($validated);

    \Log::info('Food updated:', $food->toArray());

    return redirect()->route('admin.food')->with('success', 'Food updated successfully!');
}

    public function destroy($id)
    {
        Food::destroy($id);
        return redirect()->route('admin.food')->with('success', 'Food deleted successfully.');
    }

    public function apiIndex(Request $request)
    {
        $foods = Food::orderBy('created_at', 'desc')->paginate(10);
        \Log::info('API foods fetched:', $foods->toArray());
        return response()->json($foods);
    }
}