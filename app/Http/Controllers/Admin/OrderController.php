<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Inertia\Inertia;

class OrderController extends Controller
{
    // Display a list of orders
    public function index()
    {
        $orders = Order::all(); // Fetch all orders
        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders, // Pass orders to the Vue component
        ]);
    }

    // Show a specific order's details
    public function show($id)
    {
        $order = Order::findOrFail($id); // Fetch the order by ID
        return Inertia::render('Admin/Orders/Show', [
            'order' => $order, // Pass order data to the Vue component
        ]);
    }

    // Update the order status
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id); // Find the order by ID
        $order->update(['status' => $request->status]); // Update the status
        return redirect()->route('admin.orders.index'); // Redirect to the orders index page
    }
}
