<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get dashboard data based on user role
        if ($user->isAdmin()) {
            $data = $this->getAdminDashboard();
        } elseif ($user->isHR()) {
            $data = $this->getHRDashboard();
        } elseif ($user->isManager()) {
            $data = $this->getManagerDashboard();
        } else {
            $data = $this->getEmployeeDashboard();
        }

        return view('dashboard', $data);
    }

    private function getAdminDashboard()
    {
        return [
            'totalEmployees' => Employee::count(),
            'totalDepartments' => Department::count(),
            'totalUsers' => \App\Models\User::count(),
            'presentToday' => Attendance::where('attendance_date', Carbon::today())
                ->where('status', 'present')
                ->count(),
            'pendingLeaveRequests' => LeaveRequest::where('status', 'pending')->count(),
            'recentActivity' => $this->getRecentActivity(10),
        ];
    }

    private function getHRDashboard()
    {
        return [
            'totalEmployees' => Employee::count(),
            'totalDepartments' => Department::count(),
            'presentToday' => Attendance::where('attendance_date', Carbon::today())
                ->where('status', 'present')
                ->count(),
            'pendingLeaveRequests' => LeaveRequest::where('status', 'pending')->count(),
            'recentActivity' => $this->getRecentActivity(10),
        ];
    }

    private function getManagerDashboard()
    {
        $user = auth()->user();
        $department = Department::where('manager_id', $user->id)->first();
        $departmentEmployees = $department ? $department->employees()->pluck('id') : collect();

        return [
            'teamSize' => $departmentEmployees->count(),
            'presentToday' => Attendance::where('attendance_date', Carbon::today())
                ->whereIn('employee_id', $departmentEmployees)
                ->where('status', 'present')
                ->count(),
            'pendingLeaveRequests' => LeaveRequest::where('status', 'pending')
                ->whereIn('employee_id', $departmentEmployees)
                ->count(),
            'recentActivity' => $this->getRecentActivity(5),
        ];
    }

    private function getEmployeeDashboard()
    {
        $user = auth()->user();
        $employee = $user->employee;

        if (!$employee) {
            return [
                'message' => 'Employee profile not found',
                'recentActivity' => [],
            ];
        }

        $todayAttendance = Attendance::where('employee_id', $employee->id)
            ->where('attendance_date', Carbon::today())
            ->first();

        return [
            'employee' => $employee,
            'todayAttendance' => $todayAttendance,
            'attendanceCount' => Attendance::where('employee_id', $employee->id)
                ->where('attendance_date', '>=', Carbon::now()->startOfMonth())
                ->where('status', 'present')
                ->count(),
            'leaveBalance' => 15,
            'pendingLeaveRequests' => $employee->leaveRequests()
                ->where('status', 'pending')
                ->count(),
            'recentActivity' => $employee->leaveRequests()
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    private function getRecentActivity($limit = 10)
    {
        $activities = [];

        // Get recent leave requests
        $leaveRequests = LeaveRequest::latest()
            ->take($limit)
            ->get();

        foreach ($leaveRequests as $request) {
            $activities[] = [
                'title' => 'Leave Request',
                'description' => ($request->employee?->full_name ?? 'Unknown employee') . ' submitted a leave request',
                'date' => $request->created_at,
                'status' => $request->status,
                'time' => $request->created_at?->diffForHumans() ?? '',
            ];
        }

        // Get recent attendances
        $attendances = Attendance::where('attendance_date', Carbon::today())
            ->latest()
            ->take($limit)
            ->get();

        foreach ($attendances as $attendance) {
            $activities[] = [
                'title' => 'Attendance',
                'description' => ($attendance->employee?->full_name ?? 'Unknown employee') . ' marked ' . $attendance->status,
                'date' => $attendance->created_at,
                'status' => $attendance->status,
                'time' => $attendance->created_at?->diffForHumans() ?? '',
            ];
        }

        return collect($activities)->sortByDesc('date')->take($limit)->values();
    }
}
