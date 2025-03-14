<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Inertia\Inertia;
use Illuminate\Http\Request;

class FoodController extends Controller
{
    // Display all foods with pagination
    public function index(Request $request)
    {
        // Paginate food items (10 items per page by default)
        $foods = Food::paginate(10);

        return Inertia::render('Admin/Food/Index', [
            'foods' => $foods,
        ]);
    }

    // Show the form to create a new food
    public function create()
    {
        return Inertia::render('Admin/Food/Create');
    }

    // Store a newly created food
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Food::create($validated);

        return redirect()->route('admin.food.index');  // Redirect back to the list of foods
    }

    // Show the form to edit the food
    public function edit($id)
    {
        $food = Food::findOrFail($id);
        return Inertia::render('Admin/Food/Edit', [
            'food' => $food,
        ]);
    }

    // Update the food details
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $food = Food::findOrFail($id);
        $food->update($validated);

        return redirect()->route('admin.food.index');
    }

    // Delete the food
    public function destroy($id)
    {
        Food::destroy($id);

        return redirect()->route('admin.food.index')->with('success', 'Food deleted successfully.');
    }

    public function apiIndex(Request $request)
    {
        $foods = Food::paginate(10);

        return response()->json($foods);
    }
}
