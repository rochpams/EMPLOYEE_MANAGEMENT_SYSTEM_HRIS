<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index()
    {
        return view('reports.index', $this->dashboardData());
    }

    /**
     * Route to specific report type.
     */
    public function show(string $type, Request $request)
    {
        return match ($type) {
            'employees' => $this->employeeReport($request),
            'attendance' => $this->attendanceReport($request),
            'leaves' => $this->leaveReport($request),
            'departments' => $this->departmentReport(),
            'activity' => $this->activityReport($request),
            default => redirect()->route('reports.index'),
        };
    }

    /**
     * Export a report as CSV or PDF.
     */
    public function export(string $type, string $format, Request $request)
    {
        $data = $this->reportPayload($type, $request);

        if ($format === 'csv') {
            return $this->streamCsv($data['title'], $data['headers'], $data['rows']);
        }

        if ($format === 'pdf') {
            return Pdf::loadView('reports.exports.generic', $data)
                ->download(Str::slug($data['title']).'-'.now()->format('YmdHis').'.pdf');
        }

        return redirect()->route('reports.index')->with('error', 'Unsupported export format.');
    }

    /**
     * Generate employee list report.
     */
    public function employeeReport(Request $request)
    {
        return view('reports.employees', $this->employeeReportData($request));
    }

    /**
     * Generate attendance report.
     */
    public function attendanceReport(Request $request)
    {
        return view('reports.attendance', $this->attendanceReportData($request));
    }

    /**
     * Generate leave request report.
     */
    public function leaveReport(Request $request)
    {
        return view('reports.leaves', $this->leaveReportData($request));
    }

    /**
     * Generate department report.
     */
    public function departmentReport()
    {
        return view('reports.departments', $this->departmentReportData());
    }

    /**
     * Generate monthly activity report.
     */
    public function activityReport(Request $request)
    {
        return view('reports.activity', $this->activityReportData($request));
    }

    private function dashboardData(): array
    {
        $today = Carbon::today();
        $thirtyDaysAgo = $today->copy()->subDays(29);
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        $employeeStatusCounts = collect([
            'active' => 0,
            'inactive' => 0,
            'terminated' => 0,
        ])->merge(
            Employee::query()
                ->selectRaw('employment_status, count(*) as total')
                ->groupBy('employment_status')
                ->pluck('total', 'employment_status')
        );

        $monthlyAttendances = Attendance::whereBetween('attendance_date', [
            $monthStart->toDateString(),
            $monthEnd->toDateString(),
        ])->get();

        $attendanceSummary = [
            'present' => $monthlyAttendances->filter(fn ($attendance) => $attendance->status === 'present' && is_null($attendance->time_out))->count(),
            'absent' => $monthlyAttendances->where('status', 'absent')->count(),
            'late' => $monthlyAttendances->filter(fn ($attendance) => $attendance->status === 'late' && is_null($attendance->time_out))->count(),
            'timed_out' => $monthlyAttendances->whereNotNull('time_out')->count(),
        ];

        $leaveSummary = [
            'pending' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)->count(),
            'rejected' => LeaveRequest::where('status', LeaveRequest::STATUS_REJECTED)->count(),
            'cancelled' => LeaveRequest::where('status', LeaveRequest::STATUS_CANCELLED)->count(),
        ];

        $departmentHeadcounts = Department::withCount('employees')
            ->orderBy('name')
            ->get()
            ->map(fn (Department $department) => [
                'name' => $department->name,
                'count' => $department->employees_count,
            ])
            ->values();

        if ($departmentHeadcounts->isEmpty()) {
            $departmentHeadcounts = collect([
                ['name' => 'No Departments', 'count' => 0],
            ]);
        }

        $activityStats = [
            'today_checkins' => Attendance::where('attendance_date', $today->toDateString())->count(),
            'pending_approvals' => LeaveRequest::where('status', LeaveRequest::STATUS_PENDING)->count(),
            'approved_today' => LeaveRequest::where('status', LeaveRequest::STATUS_APPROVED)
                ->whereDate('updated_at', $today->toDateString())
                ->count(),
            'new_employees' => Employee::whereDate('created_at', '>=', $monthStart)->count(),
        ];

        $stats = [
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
            'today_attendance' => Attendance::whereDate('attendance_date', $today)->count(),
        ];

        $attendanceTrend = collect(range(6, 0))->map(function (int $daysAgo) use ($today) {
            $date = $today->copy()->subDays($daysAgo);
            $records = Attendance::whereDate('attendance_date', $date->toDateString())->get();

            return [
                'label' => $date->format('D'),
                'total' => $records->count(),
                'present' => $records->filter(fn ($record) => $record->status === 'present' && is_null($record->time_out))->count(),
                'late' => $records->filter(fn ($record) => $record->status === 'late' && is_null($record->time_out))->count(),
                'timed_out' => $records->filter(fn ($record) => filled($record->time_out))->count(),
            ];
        })->values();

        $dailyTimeLogs = collect(range(6, 0))->map(function (int $daysAgo) use ($today) {
            $date = $today->copy()->subDays($daysAgo);

            return [
                'label' => $date->format('M d'),
                'total' => Attendance::whereDate('attendance_date', $date->toDateString())->count(),
            ];
        })->values();

        $weeklyTimeLogs = collect(range(3, 0))->map(function (int $weeksAgo) use ($today) {
            $startOfWeek = $today->copy()->subWeeks($weeksAgo)->startOfWeek();
            $endOfWeek = $startOfWeek->copy()->endOfWeek();

            return [
                'label' => $startOfWeek->format('M d'),
                'total' => Attendance::whereBetween('attendance_date', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])->count(),
            ];
        })->values();

        $monthlyTimeLogs = collect(range(5, 0))->map(function (int $monthsAgo) use ($today) {
            $month = $today->copy()->subMonths($monthsAgo);

            return [
                'label' => $month->format('M Y'),
                'total' => Attendance::whereYear('attendance_date', $month->year)
                    ->whereMonth('attendance_date', $month->month)
                    ->count(),
            ];
        })->values();

        $employeePerformance = Employee::with(['department', 'attendances' => function ($query) use ($thirtyDaysAgo, $today) {
            $query->whereBetween('attendance_date', [$thirtyDaysAgo->toDateString(), $today->toDateString()]);
        }])->get()->map(function (Employee $employee) {
            $attendanceCount = $employee->attendances->count();
            $presentCount = $employee->attendances->filter(fn ($attendance) => $attendance->status === 'present')->count();
            $lateCount = $employee->attendances->filter(fn ($attendance) => $attendance->status === 'late')->count();
            $timedOutCount = $employee->attendances->filter(fn ($attendance) => filled($attendance->time_out))->count();
            $hoursWorked = round($employee->attendances->sum(function ($attendance) {
                if (! $attendance->time_in || ! $attendance->time_out) {
                    return 0;
                }

                return Carbon::parse($attendance->time_in)->diffInMinutes(Carbon::parse($attendance->time_out)) / 60;
            }), 1);

            $score = $attendanceCount > 0
                ? round(($presentCount / $attendanceCount) * 100)
                : 0;

            return [
                'name' => $employee->full_name,
                'department' => $employee->department?->name ?? '-',
                'attendance_count' => $attendanceCount,
                'present_count' => $presentCount,
                'late_count' => $lateCount,
                'timed_out_count' => $timedOutCount,
                'hours_worked' => $hoursWorked,
                'score' => $score,
                'status' => $score >= 90 ? 'Excellent' : ($score >= 75 ? 'Solid' : 'Needs Attention'),
            ];
        })->sortByDesc('score')->take(5)->values();

        $workHourAnalytics = Employee::with(['department', 'attendances' => function ($query) use ($thirtyDaysAgo, $today) {
            $query->whereBetween('attendance_date', [$thirtyDaysAgo->toDateString(), $today->toDateString()]);
        }])->get()->map(function (Employee $employee) {
            $hoursWorked = round($employee->attendances->sum(function ($attendance) {
                if (! $attendance->time_in || ! $attendance->time_out) {
                    return 0;
                }

                return Carbon::parse($attendance->time_in)->diffInMinutes(Carbon::parse($attendance->time_out)) / 60;
            }), 1);

            return [
                'name' => $employee->full_name,
                'department' => $employee->department?->name ?? '-',
                'hours' => $hoursWorked,
            ];
        })->sortByDesc('hours')->take(5)->values();

        return compact(
            'stats',
            'attendanceTrend',
            'dailyTimeLogs',
            'weeklyTimeLogs',
            'monthlyTimeLogs',
            'employeePerformance',
            'workHourAnalytics',
            'employeeStatusCounts',
            'attendanceSummary',
            'leaveSummary',
            'departmentHeadcounts',
            'activityStats'
        );
    }

    private function employeeReportData(Request $request): array
    {
        $query = Employee::with('department', 'user')->latest();

        if ($request->filled('department')) {
            $query->where('department_id', $request->department);
        }

        if ($request->filled('status')) {
            $query->where('employment_status', $request->status);
        }

        $employees = $query->get();
        $departments = Department::orderBy('name')->get();

        $rows = $employees->map(function (Employee $employee) {
            return [
                $employee->full_name,
                $employee->email,
                $employee->department?->name ?? '-',
                $employee->position,
                ucfirst($employee->employment_status),
                $employee->user?->email ?? '-',
            ];
        })->all();

        return [
            'title' => 'Employee Report',
            'subtitle' => 'Current employee directory with department and account details.',
            'employees' => $employees,
            'departments' => $departments,
            'summary' => [
                ['label' => 'Total Employees', 'value' => $employees->count()],
                ['label' => 'Active', 'value' => $employees->where('employment_status', 'active')->count()],
                ['label' => 'Inactive', 'value' => $employees->where('employment_status', 'inactive')->count()],
                ['label' => 'Terminated', 'value' => $employees->where('employment_status', 'terminated')->count()],
            ],
            'headers' => ['Employee', 'Email', 'Department', 'Position', 'Status', 'Login Email'],
            'rows' => $rows,
            'filters' => $request->only(['department', 'status']),
        ];
    }

    private function attendanceReportData(Request $request): array
    {
        $startDate = $request->filled('start_date')
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->filled('end_date')
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        $query = Attendance::with(['employee.department'])
            ->whereBetween('attendance_date', [$startDate->toDateString(), $endDate->toDateString()]);

        if ($request->filled('employee')) {
            $query->where('employee_id', $request->employee);
        }

        if ($request->filled('department')) {
            $query->whereHas('employee', function ($employeeQuery) use ($request) {
                $employeeQuery->where('department_id', $request->department);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'timed_out') {
                $query->whereNotNull('time_out');
            } else {
                $query->where('status', $request->status);
            }
        }

        $attendances = $query->orderByDesc('attendance_date')->orderByDesc('time_in')->get();
        $employees = Employee::with('department')->orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();

        $summary = [
            'present' => $attendances->filter(fn ($attendance) => $attendance->status === 'present' && is_null($attendance->time_out))->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->filter(fn ($attendance) => $attendance->status === 'late' && is_null($attendance->time_out))->count(),
            'timed_out' => $attendances->whereNotNull('time_out')->count(),
        ];

        $rows = $attendances->map(function (Attendance $record) {
            return [
                $record->employee?->full_name ?? '-',
                $record->employee?->department?->name ?? '-',
                $record->attendance_date->format('M d, Y'),
                $record->time_in ? Carbon::parse($record->time_in)->format('H:i:s') : '-',
                $record->time_out ? Carbon::parse($record->time_out)->format('H:i:s') : '-',
                $record->time_out ? 'Timed Out' : ucfirst($record->status),
            ];
        })->all();

        $trend = collect(range(6, 0))->map(function (int $daysAgo) use ($endDate) {
            $date = $endDate->copy()->subDays($daysAgo);
            $records = Attendance::whereDate('attendance_date', $date->toDateString())->get();

            return [
                'label' => $date->format('D'),
                'total' => $records->count(),
            ];
        })->values();

        return [
            'title' => 'Attendance Report',
            'subtitle' => 'Review presence, late arrivals, time out activity, and work logs.',
            'attendances' => $attendances,
            'employees' => $employees,
            'departments' => $departments,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'summary' => $summary,
            'trend' => $trend,
            'headers' => ['Employee', 'Department', 'Date', 'Time In', 'Time Out', 'Status'],
            'rows' => $rows,
            'filters' => $request->only(['employee', 'department', 'status', 'start_date', 'end_date']),
        ];
    }

    private function leaveReportData(Request $request): array
    {
        $query = LeaveRequest::with('employee', 'approvedBy');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->leave_type);
        }

        $leaves = $query->latest()->get();

        $rows = $leaves->map(function (LeaveRequest $leave) {
            return [
                $leave->employee?->full_name ?? '-',
                str_replace('_', ' ', ucfirst($leave->leave_type)),
                Carbon::parse($leave->start_date)->format('M d, Y'),
                Carbon::parse($leave->end_date)->format('M d, Y'),
                ucfirst($leave->status),
                $leave->approvedBy?->name ?? '-',
            ];
        })->all();

        return [
            'title' => 'Leave Request Report',
            'subtitle' => 'Review leave volume, approval status, and approvers.',
            'leaves' => $leaves,
            'summary' => [
                'pending' => $leaves->where('status', 'pending')->count(),
                'approved' => $leaves->where('status', 'approved')->count(),
                'rejected' => $leaves->where('status', 'rejected')->count(),
                'total' => $leaves->count(),
            ],
            'headers' => ['Employee', 'Leave Type', 'Start Date', 'End Date', 'Status', 'Approved By'],
            'rows' => $rows,
            'filters' => $request->only(['status', 'leave_type']),
        ];
    }

    private function departmentReportData(): array
    {
        $departments = Department::with('manager', 'employees')->withCount('employees')->orderBy('name')->get();

        $rows = $departments->map(function (Department $department) {
            return [
                $department->name,
                $department->manager?->name ?? '-',
                (string) $department->employees_count,
                ucfirst($department->status),
            ];
        })->all();

        return [
            'title' => 'Department Report',
            'subtitle' => 'View department coverage, managers, and employee counts.',
            'departments' => $departments,
            'summary' => [
                ['label' => 'Departments', 'value' => $departments->count()],
                ['label' => 'Employees', 'value' => $departments->sum('employees_count')],
                ['label' => 'Managed Departments', 'value' => $departments->whereNotNull('manager_id')->count()],
                ['label' => 'Open Departments', 'value' => $departments->where('status', 'active')->count()],
            ],
            'headers' => ['Department', 'Manager', 'Employees', 'Status'],
            'rows' => $rows,
        ];
    }

    private function activityReportData(Request $request): array
    {
        $month = $request->filled('month') ? (int) $request->month : now()->month;
        $year = $request->filled('year') ? (int) $request->year : now()->year;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $stats = [
            'today_checkins' => Attendance::where('attendance_date', Carbon::now()->toDateString())->count(),
            'pending_approvals' => LeaveRequest::where('status', 'pending')->count(),
            'approved_today' => LeaveRequest::where('status', 'approved')->whereDate('updated_at', Carbon::now()->toDateString())->count(),
            'new_employees' => Employee::whereDate('created_at', '>=', $startDate)->count(),
        ];

        $activities = [
            ['title' => 'Attendance Logged', 'description' => 'Employee self-service time in/out recorded.', 'time' => 'Today'],
            ['title' => 'Leave Approved', 'description' => 'Leave request approved by a manager or HR.', 'time' => 'Yesterday'],
            ['title' => 'Employee Added', 'description' => 'New employee onboarding completed.', 'time' => '2 days ago'],
        ];

        $rows = collect($activities)->map(function (array $activity) {
            return [$activity['title'], $activity['description'], $activity['time']];
        })->all();

        return [
            'title' => 'Activity Report',
            'subtitle' => 'Operational events and HR highlights for the selected period.',
            'stats' => $stats,
            'activities' => $activities,
            'headers' => ['Title', 'Description', 'Time'],
            'rows' => $rows,
        ];
    }

    private function reportPayload(string $type, Request $request): array
    {
        return match ($type) {
            'employees' => $this->employeeReportData($request),
            'attendance' => $this->attendanceReportData($request),
            'leaves' => $this->leaveReportData($request),
            'departments' => $this->departmentReportData(),
            'activity' => $this->activityReportData($request),
            default => abort(404),
        };
    }

    private function streamCsv(string $title, array $headers, array $rows)
    {
        $filename = Str::slug($title).'-'.now()->format('YmdHis').'.csv';

        return response()->streamDownload(function () use ($headers, $rows) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $headers);

            foreach ($rows as $row) {
                fputcsv($output, $row);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}