<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $employee = $user?->employee;
        $today = Carbon::today();

        $query = Attendance::with(['employee.department'])->orderByDesc('attendance_date')->orderByDesc('time_in');
        $visibleEmployeeIds = $this->visibleEmployeeIds($user);

        if (is_array($visibleEmployeeIds)) {
            $query->whereIn('employee_id', $visibleEmployeeIds);
        }

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('employee_id') && ($user?->isAdmin() || $user?->isHR())) {
            $query->where('employee_id', $request->employee_id);
        }

        if ($request->filled('department_id') && ($user?->isAdmin() || $user?->isHR())) {
            $query->whereHas('employee', function ($employeeQuery) use ($request) {
                $employeeQuery->where('department_id', $request->department_id);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'timed_out') {
                $query->whereNotNull('time_out');
            } else {
                $query->where('status', $request->status);
            }
        }

        $attendances = $query->paginate(15)->withQueryString();
        $employees = Employee::with('department')->orderBy('first_name')->get();
        $departments = \App\Models\Department::orderBy('name')->get();

        $totalEmployees = Employee::when(is_array($visibleEmployeeIds), function ($employeeQuery) use ($visibleEmployeeIds) {
            $employeeQuery->whereIn('id', $visibleEmployeeIds);
        })->count();

        $presentToday = Attendance::whereDate('attendance_date', $today)
            ->when(is_array($visibleEmployeeIds), fn ($attendanceQuery) => $attendanceQuery->whereIn('employee_id', $visibleEmployeeIds))
            ->where('status', 'present')
            ->whereNull('time_out')
            ->count();

        $lateArrivals = Attendance::whereDate('attendance_date', $today)
            ->when(is_array($visibleEmployeeIds), fn ($attendanceQuery) => $attendanceQuery->whereIn('employee_id', $visibleEmployeeIds))
            ->where('status', 'late')
            ->whereNull('time_out')
            ->count();

        $timedOut = Attendance::whereDate('attendance_date', $today)
            ->when(is_array($visibleEmployeeIds), fn ($attendanceQuery) => $attendanceQuery->whereIn('employee_id', $visibleEmployeeIds))
            ->whereNotNull('time_out')
            ->count();

        $absent = Employee::when(is_array($visibleEmployeeIds), fn ($employeeQuery) => $employeeQuery->whereIn('id', $visibleEmployeeIds))
            ->whereDoesntHave('attendances', function ($attendanceQuery) use ($today) {
                $attendanceQuery->whereDate('attendance_date', $today);
            })
            ->count();

        $todayAttendance = $employee
            ? Attendance::where('employee_id', $employee->id)->whereDate('attendance_date', $today)->first()
            : null;

        $todayStatus = $todayAttendance
            ? ($todayAttendance->time_out ? 'timed_out' : $todayAttendance->status)
            : 'absent';

        $attendanceHistory = $employee
            ? Attendance::where('employee_id', $employee->id)
                ->orderByDesc('attendance_date')
                ->limit(10)
                ->get()
            : collect();

        return view('attendance.index', compact(
            'attendances',
            'employees',
            'departments',
            'totalEmployees',
            'presentToday',
            'lateArrivals',
            'timedOut',
            'absent',
            'todayAttendance',
            'todayStatus',
            'attendanceHistory'
        ));
    }

    /**
     * Record time in for the authenticated employee.
     */
    public function timeIn(Request $request)
    {
        $employee = auth()->user()?->employee;

        if (! $employee) {
            return redirect()->back()->with('error', 'Attendance actions are available to employee accounts only.');
        }

        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($attendance && $attendance->time_in) {
            return redirect()->back()->with('error', 'Already timed in today');
        }

        $timeIn = Carbon::now();
        $cutoff = Carbon::today()->setTime(9, 0);

        if ($attendance) {
            $attendance->update([
                'time_in' => $timeIn,
                'status' => $timeIn->greaterThan($cutoff) ? 'late' : 'present',
            ]);
        } else {
            Attendance::create([
                'employee_id' => $employee->id,
                'attendance_date' => $today,
                'time_in' => $timeIn,
                'status' => $timeIn->greaterThan($cutoff) ? 'late' : 'present',
            ]);
        }

        return redirect()->back()->with('success', 'Time in recorded successfully');
    }

    /**
     * Record time out for the authenticated employee.
     */
    public function timeOut(Request $request)
    {
        $employee = auth()->user()?->employee;

        if (! $employee) {
            return redirect()->back()->with('error', 'Attendance actions are available to employee accounts only.');
        }

        $today = Carbon::today();
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (! $attendance || ! $attendance->time_in) {
            return redirect()->back()->with('error', 'No time in record found for today');
        }

        if ($attendance->time_out) {
            return redirect()->back()->with('error', 'Already timed out today');
        }

        $attendance->update([
            'time_out' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Time out recorded successfully');
    }

    /**
     * Mark attendance manually (kept for administrative correction flows).
     */
    public function markAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => ['required', Rule::in(['present', 'late', 'absent', 'on_leave'])],
            'remarks' => 'nullable|string',
        ]);

        Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'attendance_date' => $validated['attendance_date'],
            ],
            $validated
        );

        return redirect()->back()->with('success', 'Attendance marked successfully');
    }

    private function visibleEmployeeIds($user)
    {
        if (! $user) {
            return null;
        }

        if ($user->isEmployee() && $user->employee) {
            return [$user->employee->id];
        }

        if ($user->isManager()) {
            return Employee::whereHas('department', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            })->pluck('id')->all();
        }

        return null;
    }
}