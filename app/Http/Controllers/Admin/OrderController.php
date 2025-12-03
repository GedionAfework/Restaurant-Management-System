<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Food;
use App\Models\Table;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['food', 'customer', 'table']);

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        } else {
            // Default to today
            $query->whereDate('created_at', Carbon::today());
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('food', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'today' => [
                'total' => Order::whereDate('created_at', Carbon::today())->count(),
                'pending' => Order::whereDate('created_at', Carbon::today())->where('status', 'pending')->count(),
                'preparing' => Order::whereDate('created_at', Carbon::today())->where('status', 'preparing')->count(),
                'ready' => Order::whereDate('created_at', Carbon::today())->where('status', 'ready')->count(),
                'completed' => Order::whereDate('created_at', Carbon::today())->where('status', 'completed')->count(),
                'revenue' => Order::whereDate('created_at', Carbon::today())
                    ->where('status', 'completed')
                    ->sum('total_amount'),
            ],
        ];

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'stats' => $stats,
            'filters' => [
                'status' => $request->status ?? 'all',
                'date' => $request->date ?? Carbon::today()->format('Y-m-d'),
                'search' => $request->search ?? '',
            ],
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['food', 'customer', 'table'])->findOrFail($id);
        return Inertia::render('Admin/Orders/Show', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled',
            'kitchen_notes' => 'nullable|string|max:500',
            'order_notes' => 'nullable|string|max:500',
            'priority' => 'nullable|integer|min:0|max:10',
        ]);

        $updateData = ['status' => $validated['status']];
        
        if (isset($validated['kitchen_notes'])) {
            $updateData['kitchen_notes'] = $validated['kitchen_notes'];
        }
        
        if (isset($validated['order_notes'])) {
            $updateData['order_notes'] = $validated['order_notes'];
        }
        
        if (isset($validated['priority'])) {
            $updateData['priority'] = $validated['priority'];
        }

        if ($validated['status'] === 'preparing' && !$order->preparing_at) {
            $updateData['preparing_at'] = Carbon::now();
            if ($order->food->preparation_time) {
                $updateData['estimated_completion_at'] = Carbon::now()
                    ->addMinutes($order->food->preparation_time * $order->quantity);
            }
        }

        if ($validated['status'] === 'ready' && !$order->ready_at) {
            $updateData['ready_at'] = Carbon::now();
        }

        if ($validated['status'] === 'completed' && !$order->completed_at) {
            $updateData['completed_at'] = Carbon::now();
        }

        $order->update($updateData);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully!');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'table_id' => 'nullable|exists:tables,table_id',
            'food_id' => 'required|exists:food,id',
            'quantity' => 'required|integer|min:1|max:99',
            'order_notes' => 'nullable|string|max:500',
            'variant_id' => 'nullable|exists:menu_variants,id',
            'add_ons' => 'nullable|array',
            'add_ons.*' => 'exists:menu_add_ons,id',
        ]);

        $food = Food::find($validated['food_id']);
        
        // Calculate price with variant and add-ons
        $basePrice = $food->price;
        $variantPrice = 0;
        
        if (isset($validated['variant_id'])) {
            $variant = $food->variants()->find($validated['variant_id']);
            if ($variant) {
                $variantPrice = $variant->price_modifier;
            }
        }
        
        $addOnsPrice = 0;
        if (isset($validated['add_ons']) && count($validated['add_ons']) > 0) {
            $addOns = $food->addOns()->whereIn('id', $validated['add_ons'])->get();
            $addOnsPrice = $addOns->sum('price');
        }
        
        $itemPrice = $basePrice + $variantPrice + $addOnsPrice;
        $totalAmount = $itemPrice * $validated['quantity'];

        $order = Order::create([
            'customer_id' => $validated['customer_id'] ?? null,
            'table_id' => $validated['table_id'] ?? null,
            'food_id' => $validated['food_id'],
            'quantity' => $validated['quantity'],
            'price' => $itemPrice,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'order_notes' => $validated['order_notes'] ?? null,
        ]);

        return response()->json([
            'message' => 'Order placed successfully!',
            'order' => $order->load(['food', 'customer', 'table']),
        ], 201);
    }
}
