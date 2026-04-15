<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendance records
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();
        
        $query = Attendance::with(['employee', 'employee.department']);
        
        // Apply filters
        if ($request->has('date') && $request->date) {
            $query->whereDate('attendance_date', $request->date);
        }
        
        if ($request->has('employee_id') && $request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($user->role === 'employee') {
            $employee = $user->employee;
            $query->where('employee_id', $employee->id);
        }
        
        $attendances = $query->orderBy('attendance_date', 'desc')->paginate(15);
        
        // Calculate statistics
        $employees = Employee::all();
        $totalEmployees = $employees->count();
        
        $presentToday = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'present')
            ->count();
        
        $lateArrivals = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'late')
            ->count();
        
        $absent = Attendance::whereDate('attendance_date', $today)
            ->where('status', 'absent')
            ->count();
        
        return view('attendance.index', compact(
            'attendances', 
            'employees', 
            'totalEmployees',
            'presentToday',
            'lateArrivals',
            'absent'
        ));
    }

    /**
     * Record time in
     */
    public function timeIn(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;
        $today = Carbon::today();

        // Check if already timed in today
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if ($attendance && $attendance->time_in) {
            return redirect()->back()->with('error', 'Already timed in today');
        }

        if ($attendance) {
            $attendance->update(['time_in' => Carbon::now()]);
        } else {
            Attendance::create([
                'employee_id' => $employee->id,
                'attendance_date' => $today,
                'time_in' => Carbon::now(),
                'status' => 'present',
            ]);
        }

        return redirect()->back()->with('success', 'Time in recorded successfully');
    }

    /**
     * Record time out
     */
    public function timeOut(Request $request)
    {
        $user = auth()->user();
        $employee = $user->employee;
        $today = Carbon::today();

        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'No time in record found for today');
        }

        if ($attendance->time_out) {
            return redirect()->back()->with('error', 'Already timed out today');
        }

        $attendance->update(['time_out' => Carbon::now()]);

        return redirect()->back()->with('success', 'Time out recorded successfully');
    }

    /**
     * Mark attendance manually (Admin/HR only)
     */
    public function markAttendance(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,late,absent,on_leave',
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
}
