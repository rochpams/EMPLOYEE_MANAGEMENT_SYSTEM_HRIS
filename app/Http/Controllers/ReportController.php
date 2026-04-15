<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index()
    {
        $stats = [
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'today_attendance' => Attendance::where('attendance_date', Carbon::now()->toDateString())->where('status', 'present')->count(),
        ];
        return view('reports.index', compact('stats'));
    }

    /**
     * Route to specific report type
     */
    public function show($type)
    {
        return match($type) {
            'employees' => $this->employeeReport(new Request()),
            'attendance' => $this->attendanceReport(new Request()),
            'leaves' => $this->leaveReport(new Request()),
            'departments' => $this->departmentReport(),
            'activity' => $this->activityReport(new Request()),
            default => redirect()->route('reports.index'),
        };
    }

    /**
     * Generate employee list report
     */
    public function employeeReport(Request $request)
    {
        $query = Employee::with('department', 'user');

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        $employees = $query->get();
        $departments = Department::all();

        return view('reports.employees', compact('employees', 'departments'));
    }

    /**
     * Generate attendance report
     */
    public function attendanceReport(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $query = Attendance::whereBetween('attendance_date', [$startDate, $endDate]);

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->with('employee')->get();
        $employees = Employee::all();

        $summary = [
            'present' => $attendances->where('status', 'present')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'on_leave' => $attendances->where('status', 'on_leave')->count(),
        ];

        return view('reports.attendance', compact('attendances', 'employees', 'startDate', 'endDate', 'summary'));
    }

    /**
     * Generate leave request report
     */
    public function leaveReport(Request $request)
    {
        $query = LeaveRequest::with('employee', 'approvedBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->get();

        $summary = [
            'pending' => $leaves->where('status', 'pending')->count(),
            'approved' => $leaves->where('status', 'approved')->count(),
            'rejected' => $leaves->where('status', 'rejected')->count(),
            'total' => $leaves->count(),
        ];

        return view('reports.leaves', compact('leaves', 'summary'));
    }

    /**
     * Generate department report
     */
    public function departmentReport()
    {
        $departments = Department::with('manager', 'employees')
            ->withCount('employees')
            ->get();

        return view('reports.departments', compact('departments'));
    }

    /**
     * Generate monthly activity report
     */
    public function activityReport(Request $request)
    {
        $month = $request->filled('month') ? $request->month : now()->month;
        $year = $request->filled('year') ? $request->year : now()->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $stats = [
            'today_checkins' => Attendance::where('attendance_date', Carbon::now()->toDateString())->count(),
            'pending_approvals' => LeaveRequest::where('status', 'pending')->count(),
            'approved_today' => LeaveRequest::where('status', 'approved')->whereDate('updated_at', Carbon::now()->toDateString())->count(),
            'new_employees' => Employee::whereDate('created_at', '>=', $startDate)->count(),
        ];

        $activities = [
            ['title' => 'Attendance Marked', 'description' => 'Employee attendance recorded', 'time' => 'Today'],
            ['title' => 'Leave Approved', 'description' => 'Leave request approved', 'time' => 'Yesterday'],
            ['title' => 'Employee Added', 'description' => 'New employee registered', 'time' => '2 days ago'],
        ];

        return view('reports.activity', compact('stats', 'activities'));
    }
}
