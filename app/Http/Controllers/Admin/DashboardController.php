<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Food;
use App\Models\Customer;
use App\Models\Table;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics and charts
     */
    public function index()
    {
        $now = Carbon::now();
        $startOfToday = $now->copy()->startOfDay();
        $startOfWeek = $now->copy()->startOfWeek();
        $startOfMonth = $now->copy()->startOfMonth();

        // Revenue Statistics
        $revenueToday = Order::whereDate('created_at', $startOfToday)->sum('total_amount');
        $revenueWeek = Order::where('created_at', '>=', $startOfWeek)->sum('total_amount');
        $revenueMonth = Order::where('created_at', '>=', $startOfMonth)->sum('total_amount');

        // Order Statistics
        $ordersToday = Order::whereDate('created_at', $startOfToday)->count();
        $ordersWeek = Order::where('created_at', '>=', $startOfWeek)->count();
        $ordersMonth = Order::where('created_at', '>=', $startOfMonth)->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Other Statistics
        $totalCustomers = Customer::count();
        $totalMenuItems = Food::count();
        $activeTables = Table::where('status', 'occupied')->count() ?? 0;
        $totalTables = Table::count() ?? 0;
        $totalEmployees = User::whereNotNull('role_id')->count();

        // Recent Orders
        $recentOrders = Order::with(['food', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Revenue Trend (Last 7 days)
        $revenueTrend = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as revenue')
        )
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Order Status Distribution
        $orderStatusDistribution = Order::select(
            'status',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('status')
            ->get();

        // Popular Menu Items (Top 5)
        $popularItems = Order::select(
            'food_id',
            DB::raw('SUM(quantity) as total_ordered'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
            ->with('food')
            ->groupBy('food_id')
            ->orderByDesc('total_ordered')
            ->limit(5)
            ->get();

        // Customer Growth (Last 7 days)
        $customerGrowth = Customer::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', $now->copy()->subDays(6)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'revenue' => [
                    'today' => round($revenueToday, 2),
                    'week' => round($revenueWeek, 2),
                    'month' => round($revenueMonth, 2),
                ],
                'orders' => [
                    'today' => $ordersToday,
                    'week' => $ordersWeek,
                    'month' => $ordersMonth,
                    'pending' => $pendingOrders,
                ],
                'customers' => [
                    'total' => $totalCustomers,
                ],
                'menu' => [
                    'total_items' => $totalMenuItems,
                ],
                'tables' => [
                    'active' => $activeTables,
                    'total' => $totalTables,
                    'available' => $totalTables - $activeTables,
                ],
                'employees' => [
                    'total' => $totalEmployees,
                ],
            ],
            'recentOrders' => $recentOrders,
            'charts' => [
                'revenueTrend' => $revenueTrend,
                'orderStatusDistribution' => $orderStatusDistribution,
                'popularItems' => $popularItems,
                'customerGrowth' => $customerGrowth,
            ],
        ]);
    }
}
