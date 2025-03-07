<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('foods')->get();
        return Inertia::render('Order/Index', ['orders' => $orders]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'food_items' => 'required|array',
        ]);

        $order = Order::create(['customer_id' => $request->customer_id]);
        $order->foods()->attach($request->food_items);

        return redirect('/orders')->with('success', 'Order placed successfully');
    }

    public function show($id)
    {
        $order = Order::with('foods')->findOrFail($id);
        return Inertia::render('Order/Show', ['order' => $order]);
    }
}
