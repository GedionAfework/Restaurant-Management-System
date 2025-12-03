<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PermissionController extends Controller
{
    /**
     * Display a listing of permissions
     */
    public function index()
    {
        $permissions = Permission::with('roles')
            ->orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');
        
        return Inertia::render('Admin/Permissions/Index', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        $modules = Permission::distinct()->pluck('module')->sort()->values();
        
        return Inertia::render('Admin/Permissions/Create', [
            'modules' => $modules,
        ]);
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['module'] . '-' . $validated['name']);

        // Check if permission with this slug already exists
        if (Permission::where('slug', $validated['slug'])->exists()) {
            return back()->withErrors(['name' => 'A permission with this name already exists in this module.']);
        }

        Permission::create($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission created successfully!');
    }

    /**
     * Show the form for editing the specified permission
     */
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $modules = Permission::distinct()->pluck('module')->sort()->values();

        return Inertia::render('Admin/Permissions/Edit', [
            'permission' => $permission,
            'modules' => $modules,
        ]);
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['module'] . '-' . $validated['name']);

        // Check if another permission with this slug already exists
        $existing = Permission::where('slug', $validated['slug'])->where('id', '!=', $id)->first();
        if ($existing) {
            return back()->withErrors(['name' => 'A permission with this name already exists in this module.']);
        }

        $permission->update($validated);

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully!');
    }

    /**
     * Remove the specified permission
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        
        // Check if permission is assigned to any roles
        if ($permission->roles()->count() > 0) {
            return redirect()->route('admin.permissions.index')
                ->with('error', 'Cannot delete permission. It is assigned to roles.');
        }

        $permission->delete();

        return redirect()->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully!');
    }
}
