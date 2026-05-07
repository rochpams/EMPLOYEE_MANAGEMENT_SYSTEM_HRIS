<x-app-layout>
    <x-slot name="title">Manager</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Manager Tools</p>
                    <h1 class="hris-title">Manager</h1>
                    <p class="hris-subtitle">
                        Review the employees assigned to your department, track attendance status, and manage the team from one place.
                    </p>
                </div>

                <div class="hris-actions">
                    <span class="hris-pill hris-pill-info">{{ $summary['total'] }} employees</span>
                    @if($department)
                        <span class="hris-pill hris-pill-neutral">{{ $department->name }}</span>
                    @endif
                </div>
            </div>
        </section>

        <section class="hris-stat-grid">
            <div class="hris-stat-card">
                <p class="hris-stat-label">Total Employees</p>
                <p class="hris-stat-value text-primary">{{ $summary['total'] }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Active</p>
                <p class="hris-stat-value text-success">{{ $summary['active'] }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Present Today</p>
                <p class="hris-stat-value text-info">{{ $summary['present_today'] }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Timed Out</p>
                <p class="hris-stat-value text-warning">{{ $summary['timed_out_today'] }}</p>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Filter Employees</h2>
                    <p class="hris-panel-subtitle">Search by name or contact, then narrow results by status and sort order.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <form method="GET" action="{{ route('manager.department-employees.index') }}" class="hris-form-grid hris-form-grid-3">
                    <div class="hris-field">
                        <label class="hris-label" for="search">Search</label>
                        <input id="search" type="search" name="search" value="{{ $filters['search'] }}" class="hris-input" placeholder="Name, email, phone, position">
                    </div>

                    <div class="hris-field">
                        <label class="hris-label" for="employment_status">Employment Status</label>
                        <select id="employment_status" name="employment_status" class="hris-select">
                            <option value="">All</option>
                            <option value="active" @selected($filters['employmentStatus'] === 'active')>Active</option>
                            <option value="inactive" @selected($filters['employmentStatus'] === 'inactive')>Inactive</option>
                            <option value="terminated" @selected($filters['employmentStatus'] === 'terminated')>Terminated</option>
                        </select>
                    </div>

                    <div class="hris-field">
                        <label class="hris-label" for="attendance_status">Attendance Status</label>
                        <select id="attendance_status" name="attendance_status" class="hris-select">
                            <option value="">All</option>
                            <option value="present" @selected($filters['attendanceStatus'] === 'present')>Present</option>
                            <option value="late" @selected($filters['attendanceStatus'] === 'late')>Late</option>
                            <option value="timed_out" @selected($filters['attendanceStatus'] === 'timed_out')>Timed Out</option>
                            <option value="on_leave" @selected($filters['attendanceStatus'] === 'on_leave')>On Leave</option>
                            <option value="absent" @selected($filters['attendanceStatus'] === 'absent')>Absent</option>
                        </select>
                    </div>

                    <div class="hris-field">
                        <label class="hris-label" for="sort">Sort By</label>
                        <select id="sort" name="sort" class="hris-select">
                            <option value="name" @selected($filters['sort'] === 'name')>Name</option>
                            <option value="position" @selected($filters['sort'] === 'position')>Position</option>
                            <option value="hire_date" @selected($filters['sort'] === 'hire_date')>Hire Date</option>
                            <option value="employment_status" @selected($filters['sort'] === 'employment_status')>Employment Status</option>
                        </select>
                    </div>

                    <div class="hris-field">
                        <label class="hris-label" for="direction">Direction</label>
                        <select id="direction" name="direction" class="hris-select">
                            <option value="asc" @selected($filters['direction'] === 'asc')>Ascending</option>
                            <option value="desc" @selected($filters['direction'] === 'desc')>Descending</option>
                        </select>
                    </div>

                    <div class="hris-form-actions align-self-end">
                        <button type="submit" class="hris-btn-primary">Apply</button>
                        <a href="{{ route('manager.department-employees.index') }}" class="hris-btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Department Employee List</h2>
                    <p class="hris-panel-subtitle">Employee details are limited to your assigned department only.</p>
                </div>

                <span class="hris-pill hris-pill-info">{{ $employees->total() }} result{{ $employees->total() === 1 ? '' : 's' }}</span>
            </div>

            <div class="hris-table-wrap">
                <table class="hris-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Position</th>
                            <th>Department</th>
                            <th>Attendance</th>
                            <th>Status</th>
                            <th>Employment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                            @php
                                $todayAttendance = $employee->attendances->first();
                                $attendanceLabel = 'Absent';
                                $attendanceClass = 'hris-pill-danger';

                                if ($todayAttendance) {
                                    if ($todayAttendance->time_out) {
                                        $attendanceLabel = 'Timed Out';
                                        $attendanceClass = 'hris-pill-info';
                                    } elseif ($todayAttendance->status === 'late') {
                                        $attendanceLabel = 'Late';
                                        $attendanceClass = 'hris-pill-warning';
                                    } elseif ($todayAttendance->status === 'on_leave') {
                                        $attendanceLabel = 'On Leave';
                                        $attendanceClass = 'hris-pill-neutral';
                                    } else {
                                        $attendanceLabel = 'Present';
                                        $attendanceClass = 'hris-pill-success';
                                    }
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $employee->full_name }}</div>
                                    <div class="small hris-muted">{{ $employee->email }}</div>
                                </td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->department?->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="hris-pill {{ $attendanceClass }}">{{ $attendanceLabel }}</span>
                                </td>
                                <td>
                                    <span class="hris-pill {{ $employee->employment_status === 'active' ? 'hris-pill-success' : ($employee->employment_status === 'inactive' ? 'hris-pill-warning' : 'hris-pill-danger') }}">
                                        {{ ucfirst($employee->employment_status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><span class="hris-muted">Hire Date:</span> {{ $employee->hire_date?->format('M d, Y') }}</div>
                                        <div><span class="hris-muted">Phone:</span> {{ $employee->phone ?? 'N/A' }}</div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="hris-empty">
                                        <div class="hris-empty-icon"><i class="bi bi-people"></i></div>
                                        <p class="mb-0">No employees match the selected filters.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="hris-panel-body border-top">
                {{ $employees->links() }}
            </div>
        </section>

        @if(! $department)
            <div class="hris-empty">
                <div class="hris-empty-icon"><i class="bi bi-exclamation-triangle"></i></div>
                <p class="mb-0">No department is assigned to your account yet.</p>
            </div>
        @endif
    </div>
</x-app-layout>