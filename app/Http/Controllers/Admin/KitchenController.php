<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class KitchenController extends Controller
{
    /**
     * Display the kitchen display system
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        
        $query = Order::with(['food', 'table', 'customer'])
            ->orderBy('created_at', 'asc');

        if ($status !== 'all') {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['pending', 'preparing', 'ready']);
        }

        $orders = $query->get()->map(function ($order) {
            // Add computed attributes for frontend
            $order->is_overdue = $order->isOverdue();
            $order->elapsed_time = $order->elapsed_time;
            $order->estimated_time_remaining = $order->estimated_time_remaining;
            return $order;
        });

        // Group orders by status for better organization
        $groupedOrders = [
            'pending' => $orders->where('status', 'pending')->values(),
            'preparing' => $orders->where('status', 'preparing')->values(),
            'ready' => $orders->where('status', 'ready')->values(),
        ];

        return Inertia::render('Admin/Kitchen/Index', [
            'orders' => $orders,
            'groupedOrders' => $groupedOrders,
            'stats' => [
                'pending' => Order::where('status', 'pending')->count(),
                'preparing' => Order::where('status', 'preparing')->count(),
                'ready' => Order::where('status', 'ready')->count(),
                'completed_today' => Order::where('status', 'completed')
                    ->whereDate('created_at', Carbon::today())
                    ->count(),
            ],
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,preparing,ready,completed,cancelled',
            'kitchen_notes' => 'nullable|string|max:500',
        ]);

        $updateData = ['status' => $validated['status']];
        
        if ($validated['status'] === 'preparing') {
            $updateData['preparing_at'] = Carbon::now();
            if ($order->food->preparation_time) {
                $updateData['estimated_completion_at'] = Carbon::now()
                    ->addMinutes($order->food->preparation_time * $order->quantity);
            }
        }

        if ($validated['status'] === 'ready') {
            $updateData['ready_at'] = Carbon::now();
        }

        if ($validated['status'] === 'completed') {
            $updateData['completed_at'] = Carbon::now();
        }

        if (isset($validated['kitchen_notes'])) {
            $updateData['kitchen_notes'] = $validated['kitchen_notes'];
        }

        $order->update($updateData);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order->load(['food', 'table', 'customer']),
        ]);
    }

    /**
     * Get orders for kitchen display (AJAX endpoint)
     */
    public function getOrders(Request $request)
    {
        $orders = Order::with(['food', 'table', 'customer'])
            ->whereIn('status', ['pending', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($orders);
    }
}

