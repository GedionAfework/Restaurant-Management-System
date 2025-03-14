<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Inertia\Inertia;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = Employee::all();
        return inertia('Admin/Employees', ['employees' => $employees]);
    }
    public function create()
    {
        return Inertia::render('Admin/Employees/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees',
            'role' => 'required|string',
        ]);

        Employee::create($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee created successfully.');
    }

    // Show the form to edit the employee
    public function edit($id)
    {
        return Inertia::render('Admin/Employees/Edit', [
            'employee' => Employee::findOrFail($id),
        ]);
    }

    // Update the employee details
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($validated);

        return redirect()->route('admin.employees.index')->with('success', 'Employee updated successfully.');
    }

    // Delete the employee
    public function destroy($id)
    {
        Employee::destroy($id);

        return redirect()->route('admin.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
