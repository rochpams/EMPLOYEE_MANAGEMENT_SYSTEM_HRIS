<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    // Show all employees
    public function index()
    {
        $employees = Employee::with('department')->get();
        return view('employees.index', compact('employees'));
    }

    // Show create form
    public function create()
    {
        $departments = Department::all();
        return view('employees.create', compact('departments'));
    }

    // Store new employee
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'employment_status' => 'required'
        ]);

        Employee::create($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee added successfully!');
    }

    // Show employee details
    public function show(Employee $employee)
    {
        $employee->load('department');
        return view('employees.show', compact('employee'));
    }

    // Show edit form
    public function edit(Employee $employee)
    {
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'departments'));
    }

    // Update employee
    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'position' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'employment_status' => 'required'
        ]);

        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
    }

    // Delete employee
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully!');
    }
}
