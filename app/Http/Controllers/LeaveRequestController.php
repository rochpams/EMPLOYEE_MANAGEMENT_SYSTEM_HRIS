<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'employee') {
            $leaveRequests = $user->employee->leaveRequests()->latest()->paginate(10);
        } else {
            $leaveRequests = LeaveRequest::with('employee', 'approvedBy')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
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
        
        $validated = $request->validate([
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
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
        return view('leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Update leave request status (Manager/HR/Admin)
     */
    public function updateStatus(Request $request, LeaveRequest $leaveRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($leaveRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Cannot modify a ' . $leaveRequest->status . ' leave request');
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
        if ($leaveRequest->employee_id !== auth()->user()->employee->id) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        if (!in_array($leaveRequest->status, ['pending', 'approved'])) {
            return redirect()->back()->with('error', 'Cannot cancel ' . $leaveRequest->status . ' request');
        }

        $leaveRequest->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Leave request cancelled successfully');
    }
}
