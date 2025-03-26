<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Food;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('food', 'customer')->orderBy('created_at', 'desc')->paginate(10);
        return Inertia::render('Admin/Orders', [
            'orders' => $orders,
        ]);
    }

    public function show($id)
    {
        $order = Order::with('food', 'customer')->findOrFail($id);
        return Inertia::render('Admin/Orders/Show', [
            'order' => $order->toArray(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);
        $order->update($validated);
        return redirect()->route('admin.orders');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|integer|min:1|max:99',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $food = Food::find($validated['food_id']);
        $order = Order::create([
            'customer_id' => $validated['customer_id'],
            'food_id' => $validated['food_id'],
            'quantity' => $validated['quantity'],
            'price' => $food->price,
            'total_amount' => $validated['total_amount'],
            'status' => 'pending',
        ]);

        \Log::info('Order created:', $order->toArray());

        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order->load('food', 'customer')->toArray(),
        ], 201);
    }
}