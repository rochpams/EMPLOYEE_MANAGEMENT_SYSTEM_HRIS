<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index()
    {
        $departments = Department::with('manager', 'employees')->get();
        $users = User::where('role', 'manager')->orWhere('role', 'admin')->get();
        return view('departments.index', compact('departments', 'users'));
    }

    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        $managers = User::where('role', 'manager')->orWhere('role', 'admin')->get();
        return view('departments.create', compact('managers'));
    }

    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        Department::create($validated);

        return redirect()->route('departments.index')->with('success', 'Department created successfully');
    }

    /**
     * Display the specified department
     */
    public function show(Department $department)
    {
        $department->load('manager', 'employees');
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the department
     */
    public function edit(Department $department)
    {
        $managers = User::where('role', 'manager')->orWhere('role', 'admin')->get();
        return view('departments.edit', compact('department', 'managers'));
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $department->update($validated);

        return redirect()->route('departments.show', $department)->with('success', 'Department updated successfully');
    }

    /**
     * Remove the specified department
     */
    public function destroy(Department $department)
    {
        if ($department->employees()->count() > 0) {
            return redirect()->route('departments.index')->with('error', 'Cannot delete department with employees');
        }

        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully');
    }
}
