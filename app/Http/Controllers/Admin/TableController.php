<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

class TableController extends Controller
{
    /**
     * Display a listing of tables
     */
    public function index(Request $request)
    {
        $query = Table::query();

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by zone
        if ($request->has('zone') && $request->zone !== 'all') {
            $query->where('zone', $request->zone);
        }

        // Filter by floor
        if ($request->has('floor') && $request->floor !== 'all') {
            $query->where('floor', $request->floor);
        }

        $tables = $query->orderBy('table_number')->get();
        
        // Get statistics
        $stats = [
            'total' => Table::count(),
            'available' => Table::where('status', 'available')->count(),
            'occupied' => Table::where('status', 'occupied')->count(),
            'reserved' => Table::where('status', 'reserved')->count(),
            'cleaning' => Table::where('status', 'cleaning')->count(),
        ];

        // Get unique zones and floors for filters
        $zones = Table::distinct()->whereNotNull('zone')->pluck('zone')->sort()->values();
        $floors = Table::distinct()->pluck('floor')->sort()->values();

        return Inertia::render('Admin/Tables/Index', [
            'tables' => $tables,
            'stats' => $stats,
            'filters' => [
                'status' => $request->status ?? 'all',
                'zone' => $request->zone ?? 'all',
                'floor' => $request->floor ?? 'all',
            ],
            'zones' => $zones,
            'floors' => $floors,
        ]);
    }

    /**
     * Show the form for creating a new table
     */
    public function create()
    {
        return Inertia::render('Admin/Tables/Create');
    }

    /**
     * Store a newly created table
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|string|max:255|unique:tables,table_number',
            'capacity' => 'required|integer|min:1|max:20',
            'location' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:50',
            'shape' => 'required|in:round,square,rectangular,booth',
            'floor' => 'required|integer|min:1|max:10',
            'status' => 'required|in:available,occupied,reserved,cleaning',
            'position_x' => 'nullable|integer|min:0|max:100',
            'position_y' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $table = Table::create($validated);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table created successfully!');
    }

    /**
     * Display the specified table
     */
    public function show($id)
    {
        $table = Table::with(['orders', 'reservations', 'activeOrder'])->findOrFail($id);
        
        return Inertia::render('Admin/Tables/Show', [
            'table' => $table,
        ]);
    }

    /**
     * Show the form for editing the specified table
     */
    public function edit($id)
    {
        $table = Table::findOrFail($id);
        
        return Inertia::render('Admin/Tables/Edit', [
            'table' => $table,
        ]);
    }

    /**
     * Update the specified table
     */
    public function update(Request $request, $id)
    {
        $table = Table::findOrFail($id);

        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tables', 'table_number')->ignore($table->table_id, 'table_id'),
            ],
            'capacity' => 'required|integer|min:1|max:20',
            'location' => 'nullable|string|max:255',
            'zone' => 'nullable|string|max:50',
            'shape' => 'required|in:round,square,rectangular,booth',
            'floor' => 'required|integer|min:1|max:10',
            'status' => 'required|in:available,occupied,reserved,cleaning',
            'position_x' => 'nullable|integer|min:0|max:100',
            'position_y' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        $table->update($validated);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table updated successfully!');
    }

    /**
     * Remove the specified table
     */
    public function destroy($id)
    {
        $table = Table::findOrFail($id);

        // Check if table has active orders
        if ($table->activeOrder()->exists()) {
            return redirect()->route('admin.tables.index')
                ->with('error', 'Cannot delete table. It has active orders.');
        }

        // Check if table has reservations
        if ($table->reservations()->where('status', 'confirmed')->exists()) {
            return redirect()->route('admin.tables.index')
                ->with('error', 'Cannot delete table. It has confirmed reservations.');
        }

        $table->delete();

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table deleted successfully!');
    }

    /**
     * Get table layout for visual builder
     */
    public function layout(Request $request)
    {
        $floor = $request->get('floor', 1);
        $tables = Table::where('floor', $floor)
            ->whereNotNull('position_x')
            ->whereNotNull('position_y')
            ->get();

        return response()->json($tables);
    }

    /**
     * Update table positions (for visual builder)
     */
    public function updatePositions(Request $request)
    {
        $request->validate([
            'tables' => 'required|array',
            'tables.*.table_id' => 'required|exists:tables,table_id',
            'tables.*.position_x' => 'required|integer|min:0|max:100',
            'tables.*.position_y' => 'required|integer|min:0|max:100',
        ]);

        foreach ($request->tables as $tableData) {
            Table::where('table_id', $tableData['table_id'])->update([
                'position_x' => $tableData['position_x'],
                'position_y' => $tableData['position_y'],
            ]);
        }

        return response()->json(['message' => 'Table positions updated successfully']);
    }

    /**
     * Assign table to order
     */
    public function assignToOrder(Request $request, $id)
    {
        $table = Table::findOrFail($id);
        $order = Order::findOrFail($request->order_id);

        // Check if table is available
        if (!$table->isAvailable()) {
            return response()->json([
                'error' => 'Table is not available'
            ], 400);
        }

        // Check if table can accommodate
        if (!$table->canAccommodate($request->party_size ?? 2)) {
            return response()->json([
                'error' => 'Table cannot accommodate this party size'
            ], 400);
        }

        $order->update(['table_id' => $table->table_id]);
        $table->update(['status' => 'occupied']);

        return response()->json([
            'message' => 'Table assigned successfully',
            'table' => $table,
            'order' => $order,
        ]);
    }

    /**
     * Release table (mark as available)
     */
    public function release($id)
    {
        $table = Table::findOrFail($id);
        
        // Check if table has active orders
        if ($table->activeOrder()->exists()) {
            return redirect()->route('admin.tables.index')
                ->with('error', 'Cannot release table. It has active orders.');
        }

        $table->update(['status' => 'available']);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table released successfully!');
    }
}
