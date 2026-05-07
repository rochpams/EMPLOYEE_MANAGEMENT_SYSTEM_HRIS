<x-app-layout>
    <x-slot name="title">Attendance</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Attendance</p>
                    <h1 class="hris-title">Time In, Time Out, and Attendance History</h1>
                    <p class="hris-subtitle">Employees control their own attendance actions. Admins, HR, and managers can monitor records, trends, and exceptions from the same page.</p>
                </div>

                <span class="hris-pill hris-pill-info">{{ $totalEmployees ?? count($employees) }} employees</span>
            </div>
        </section>

        <section class="hris-stat-grid">
            <div class="hris-stat-card">
                <p class="hris-stat-label">Total Employees</p>
                <p class="hris-stat-value text-primary">{{ $totalEmployees ?? count($employees) }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Present Today</p>
                <p class="hris-stat-value text-success">{{ $presentToday ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Late Arrivals</p>
                <p class="hris-stat-value text-warning">{{ $lateArrivals ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Timed Out</p>
                <p class="hris-stat-value text-info">{{ $timedOut ?? 0 }}</p>
            </div>
        </section>

        @if(auth()->user()->isEmployee())
            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">My Attendance Status</h2>
                        <p class="hris-panel-subtitle">Your current day status is linked to your employee profile.</p>
                    </div>

                    <span class="hris-pill {{ $todayStatus === 'timed_out' ? 'hris-pill-info' : ($todayStatus === 'late' ? 'hris-pill-warning' : ($todayStatus === 'present' ? 'hris-pill-success' : 'hris-pill-danger')) }}">
                        {{ $todayStatus === 'timed_out' ? 'Timed Out' : ucfirst($todayStatus) }}
                    </span>
                </div>

                <div class="hris-panel-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="hris-stat-card h-100">
                                <p class="hris-stat-label">Today</p>
                                <p class="hris-stat-value text-primary">{{ now()->format('M d, Y') }}</p>
                                <p class="mt-3 small hris-muted">Time in: {{ $todayAttendance?->time_in ? $todayAttendance->time_in->format('H:i:s') : 'Not yet recorded' }}</p>
                                <p class="mt-1 small hris-muted">Time out: {{ $todayAttendance?->time_out ? $todayAttendance?->time_out->format('H:i:s') : 'Not yet recorded' }}</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-8">
                            <div class="hris-soft-panel h-100">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6">
                                        <form action="{{ route('attendance.time-in') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="hris-btn-primary w-100" @disabled($todayAttendance?->time_in)>Time In</button>
                                        </form>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        <form action="{{ route('attendance.time-out') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="hris-btn-secondary w-100" @disabled(! $todayAttendance?->time_in || $todayAttendance?->time_out)>Time Out</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="mt-4 hris-surface-muted p-4 small hris-muted">
                                    <p class="fw-semibold hris-text-dark mb-2">Status guide</p>
                                    <p class="mb-0">Present means you are checked in. Late means you checked in after the cutoff. Timed Out means the current day has a completed exit log. Absent is shown when no attendance exists for the day.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">My Attendance History</h2>
                        <p class="hris-panel-subtitle">A quick view of your latest attendance records.</p>
                    </div>
                </div>

                <div class="hris-table-wrap">
                    <table class="hris-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($attendanceHistory as $record)
                                <tr>
                                    <td>{{ $record->attendance_date->format('M d, Y') }}</td>
                                    <td>{{ $record->time_in ? $record->time_in->format('H:i:s') : '-' }}</td>
                                    <td>{{ $record->time_out ? $record->time_out->format('H:i:s') : '-' }}</td>
                                    <td>
                                        <span class="hris-pill {{ $record->time_out ? 'hris-pill-info' : ($record->status === 'late' ? 'hris-pill-warning' : 'hris-pill-success') }}">
                                            {{ $record->time_out ? 'Timed Out' : ucfirst($record->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="hris-empty">
                                            <div class="hris-empty-icon"><i class="bi bi-clock-history"></i></div>
                                            <p>No attendance history available yet.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">Monitor Attendance</h2>
                        <p class="hris-panel-subtitle">Filter by employee, department, date, and status.</p>
                    </div>
                </div>

                <div class="hris-panel-body">
                    <form action="{{ route('attendance.index') }}" method="GET" class="hris-filter-grid">
                        <div class="hris-field">
                            <label class="hris-label">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="hris-input">
                        </div>

                        <div class="hris-field">
                            <label class="hris-label">Employee</label>
                            <select name="employee_id" class="hris-select">
                                <option value="">All Employees</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="hris-field">
                            <label class="hris-label">Department</label>
                            <select name="department_id" class="hris-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="hris-field">
                            <label class="hris-label">Status</label>
                            <select name="status" class="hris-select">
                                <option value="">All Statuses</option>
                                <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                                <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                                <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                                <option value="timed_out" {{ request('status') === 'timed_out' ? 'selected' : '' }}>Timed Out</option>
                            </select>
                        </div>

                        <div class="hris-form-actions hris-form-span">
                            <button type="submit" class="hris-btn-primary">Apply Filters</button>
                            <a href="{{ route('attendance.index') }}" class="hris-btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </section>
        @endif

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Attendance Records</h2>
                    <p class="hris-panel-subtitle">Latest activity with department context and clear status labels.</p>
                </div>

                <span class="hris-pill hris-pill-info">
                    {{ $attendances->total() }} {{ $attendances->total() === 1 ? 'record' : 'records' }}
                </span>
            </div>

            <div class="hris-table-wrap">
                <table class="hris-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Department</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $record)
                            <tr>
                                <td>{{ $record->attendance_date->format('m/d/Y') }}</td>
                                <td>{{ $record->employee?->first_name }} {{ $record->employee?->last_name }}</td>
                                <td>{{ $record->employee?->department?->name ?? '-' }}</td>
                                <td>{{ $record->time_in ? $record->time_in->format('H:i:s') : '-' }}</td>
                                <td>{{ $record->time_out ? $record->time_out->format('H:i:s') : '-' }}</td>
                                <td>
                                    @if($record->time_out)
                                        <span class="hris-pill hris-pill-info">Timed Out</span>
                                    @elseif($record->status === 'present')
                                        <span class="hris-pill hris-pill-success">Present</span>
                                    @elseif($record->status === 'absent')
                                        <span class="hris-pill hris-pill-danger">Absent</span>
                                    @elseif($record->status === 'late')
                                        <span class="hris-pill hris-pill-warning">Late</span>
                                    @else
                                        <span class="hris-pill hris-pill-neutral">{{ ucfirst($record->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="hris-empty">
                                        <div class="hris-empty-icon"><i class="bi bi-file-earmark-text"></i></div>
                                        <p>No attendance records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="border-top px-4 px-md-5 py-3 small hris-muted">
                Showing {{ $attendances->count() }} attendance {{ $attendances->count() === 1 ? 'record' : 'records' }} on this page.
            </div>
        </section>
    </div>
</x-app-layout>