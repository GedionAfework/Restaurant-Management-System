<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{

    public function index()
    {
        // Get all users (employees) with their roles
        $employees = User::with('role')->get();
        return inertia('Admin/Employees', ['employees' => $employees]);
    }
    
    public function create()
    {
        // Get available roles for selection
        $roles = Role::where('is_system', false)->get();
        return Inertia::render('Admin/Employees/Create', [
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Create user with hashed password
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'is_admin' => false,
        ]);

        return redirect()->route('admin.employees')->with('success', 'Employee created successfully.');
    }

    // Show the form to edit the employee
    public function edit($id)
    {
        $employee = User::with('role')->findOrFail($id);
        $roles = Role::where('is_system', false)->get();
        
        return Inertia::render('Admin/Employees/Edit', [
            'employee' => $employee,
            'roles' => $roles,
        ]);
    }

    // Update the employee details
    public function update(Request $request, $id)
    {
        $employee = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Update user
        $employee->name = $validated['name'];
        $employee->email = $validated['email'];
        $employee->role_id = $validated['role_id'];
        
        // Only update password if provided
        if (!empty($validated['password'])) {
            $employee->password = Hash::make($validated['password']);
        }
        
        $employee->save();

        return redirect()->route('admin.employees')->with('success', 'Employee updated successfully.');
    }

    // Delete the employee
    public function destroy($id)
    {
        $employee = User::findOrFail($id);
        
        // Prevent deleting the current user
        if ($employee->id === auth()->id()) {
            return redirect()->route('admin.employees')->with('error', 'You cannot delete your own account.');
        }
        
        $employee->delete();

        return redirect()->route('admin.employees')->with('success', 'Employee deleted successfully.');
    }
}
