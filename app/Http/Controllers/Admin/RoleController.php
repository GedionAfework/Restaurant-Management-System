<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of roles
     */
    public function index()
    {
        $roles = Role::with('permissions')->withCount('users')->paginate(15);
        return Inertia::render('Admin/Roles/Index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');
        return Inertia::render('Admin/Roles/Create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_system'] = false;

        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role = Role::create($validated);

        if (!empty($permissions)) {
            $role->assignPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully!');
    }

    /**
     * Display the specified role
     */
    public function show($id)
    {
        $role = Role::with('permissions', 'users')->findOrFail($id);
        return Inertia::render('Admin/Roles/Show', [
            'role' => $role,
        ]);
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::orderBy('module')->orderBy('name')->get()->groupBy('module');

        // Prevent editing system roles
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be edited.');
        }

        return Inertia::render('Admin/Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Prevent updating system roles
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be updated.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $permissions = $validated['permissions'] ?? [];
        unset($validated['permissions']);

        $role->update($validated);

        if (isset($permissions)) {
            $role->assignPermissions($permissions);
        }

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully!');
    }

    /**
     * Remove the specified role
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        // Prevent deleting system roles
        if ($role->is_system) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'System roles cannot be deleted.');
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role. It is assigned to users.');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully!');
    }
}
