<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DepartmentEmployeeController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $department = Department::withCount('employees')
            ->where('manager_id', $user->id)
            ->first();

        $today = Carbon::today()->toDateString();
        $search = trim((string) $request->string('search'));
        $employmentStatus = $request->string('employment_status')->toString();
        $attendanceStatus = $request->string('attendance_status')->toString();
        $sort = $request->string('sort')->toString() ?: 'name';
        $direction = $request->string('direction')->toString() ?: 'asc';

        $query = Employee::query()
            ->with([
                'department',
                'user',
                'attendances' => function ($attendanceQuery) use ($today) {
                    $attendanceQuery->whereDate('attendance_date', $today);
                },
            ])
            ->when($department, function ($employeeQuery) use ($department) {
                $employeeQuery->where('department_id', $department->id);
            }, function ($employeeQuery) {
                $employeeQuery->whereRaw('1 = 0');
            });

        if ($search !== '') {
            $query->where(function ($employeeQuery) use ($search) {
                $employeeQuery->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('position', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($employmentStatus !== '') {
            $query->where('employment_status', $employmentStatus);
        }

        if ($attendanceStatus !== '') {
            $query->where(function ($employeeQuery) use ($attendanceStatus, $today) {
                if ($attendanceStatus === 'absent') {
                    $employeeQuery->whereDoesntHave('attendances', function ($attendanceQuery) use ($today) {
                        $attendanceQuery->whereDate('attendance_date', $today);
                    });

                    return;
                }

                $employeeQuery->whereHas('attendances', function ($attendanceQuery) use ($attendanceStatus, $today) {
                    $attendanceQuery->whereDate('attendance_date', $today);

                    if ($attendanceStatus === 'timed_out') {
                        $attendanceQuery->whereNotNull('time_out');

                        return;
                    }

                    $attendanceQuery->where('status', $attendanceStatus)
                        ->whereNull('time_out');
                });
            });
        }

        $sortMap = [
            'name' => ['first_name', 'last_name'],
            'position' => ['position'],
            'hire_date' => ['hire_date'],
            'employment_status' => ['employment_status'],
        ];

        $direction = $direction === 'desc' ? 'desc' : 'asc';
        $sortColumns = $sortMap[$sort] ?? $sortMap['name'];

        foreach ($sortColumns as $sortColumn) {
            $query->orderBy($sortColumn, $direction);
        }

        $employees = $query->paginate(10)->withQueryString();

        $departmentEmployeeIds = $department
            ? Employee::where('department_id', $department->id)->pluck('id')
            : collect();

        $summary = [
            'total' => $department?->employees_count ?? 0,
            'active' => $department ? Employee::where('department_id', $department->id)->where('employment_status', 'active')->count() : 0,
            'present_today' => $department ? Employee::where('department_id', $department->id)
                ->whereHas('attendances', function ($attendanceQuery) use ($today) {
                    $attendanceQuery->whereDate('attendance_date', $today)
                        ->where('status', 'present')
                        ->whereNull('time_out');
                })->count() : 0,
            'timed_out_today' => $department ? Employee::where('department_id', $department->id)
                ->whereHas('attendances', function ($attendanceQuery) use ($today) {
                    $attendanceQuery->whereDate('attendance_date', $today)
                        ->whereNotNull('time_out');
                })->count() : 0,
        ];

        return view('manager.department-employees.index', [
            'department' => $department,
            'employees' => $employees,
            'summary' => $summary,
            'filters' => [
                'search' => $search,
                'employmentStatus' => $employmentStatus,
                'attendanceStatus' => $attendanceStatus,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }
}