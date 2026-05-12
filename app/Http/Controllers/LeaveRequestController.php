<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        if ($user->role === 'employee') {
            $leaveRequests = $user->employee
                ? $user->employee->leaveRequests()->latest()->paginate(10)
                : collect([]);
        } else {
            $query = LeaveRequest::with('employee', 'approvedBy')->orderBy('created_at', 'desc');

            if ($user->isManager()) {
                $managedDepartmentIds = $user->managedDepartments()->pluck('id');
                $query->whereHas('employee', function ($employeeQuery) use ($managedDepartmentIds) {
                    $employeeQuery->whereIn('department_id', $managedDepartmentIds);
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $leaveRequests = $query->paginate(10);
        }
        
        return view('leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new leave request
     */
    public function create()
    {
        return view('leave-requests.create');
    }

    /**
     * Store a newly created leave request
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee profile not found');
        }
        
        $validated = $request->validate([
            'leave_type' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string'],
        ]);

        $validated['employee_id'] = $employee->id;
        $validated['status'] = 'pending';

        LeaveRequest::create($validated);

        return redirect()->route('leave-requests.index')->with('success', 'Leave request submitted successfully');
    }

    /**
     * Display the specified leave request
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $user = auth()->user();

        if ($user->isEmployee()) {
            if (!$user->employee || $leaveRequest->employee_id !== $user->employee->id) {
                return redirect()->route('leave-requests.index')->with('error', 'Unauthorized');
            }
        }

        if ($user->isManager()) {
            $managedDepartmentIds = $user->managedDepartments()->pluck('id');
            $isInManagedDepartment = $leaveRequest->employee()
                ->whereIn('department_id', $managedDepartmentIds)
                ->exists();

            if (!$isInManagedDepartment) {
                return redirect()->route('leave-requests.index')->with('error', 'Unauthorized');
            }
        }

        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Update leave request status (Manager/HR/Admin)
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['approved', 'rejected'])],
        ]);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot modify a ' . $leaveRequest->status . ' leave request');
        }

        $actor = auth()->user();
        // Only managers can approve or reject leave requests
        if (!$actor->isManager()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Ensure manager is responsible for the employee's department
        $managedDepartmentIds = $actor->managedDepartments()->pluck('id')->toArray();
        $employee = $leaveRequest->employee;
        if (!$employee || !in_array($employee->department_id, $managedDepartmentIds)) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $leaveRequest->update([
            'status' => $validated['status'],
            'approved_by' => $validated['status'] !== 'pending' ? auth()->id() : null,
        ]);

        return redirect()->back()->with('success', 'Leave request ' . $validated['status'] . ' successfully');
    }

    /**
     * Cancel leave request
     */
    public function cancel(LeaveRequest $leaveRequest)
    {
        $employee = auth()->user()->employee;

        if (!$employee || $leaveRequest->employee_id !== $employee->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        if (!in_array($leaveRequest->status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'Cannot cancel ' . $leaveRequest->status . ' request');
        }

        $leaveRequest->update(['status' => LeaveRequest::STATUS_CANCELLED]);

        return redirect()->back()->with('success', 'Leave request cancelled successfully');
    }
}
