<x-app-layout>
    <x-slot name="title">Attendance Report</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Reports</p>
                    <h1 class="hris-title">Attendance Report</h1>
                    <p class="hris-subtitle">Review presence, absence, late arrivals, and leave-related attendance status in one place.</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('reports.export', ['type' => 'attendance', 'format' => 'csv', 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'employee' => request('employee'), 'department' => request('department'), 'status' => request('status')]) }}" class="hris-btn-secondary">CSV Export</a>
                    <a href="{{ route('reports.export', ['type' => 'attendance', 'format' => 'pdf', 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'employee' => request('employee'), 'department' => request('department'), 'status' => request('status')]) }}" class="hris-btn-primary">PDF Export</a>
                    <a href="{{ route('reports.index') }}" class="hris-btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
                </div>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Filter Attendance</h2>
                    <p class="hris-panel-subtitle">Narrow the report by employee, department, status, or date range.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <form action="{{ route('reports.attendance') }}" method="GET" class="hris-filter-grid">
                    <div class="hris-field">
                        <label class="hris-label">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}" class="hris-input">
                    </div>

                    <div class="hris-field">
                        <label class="hris-label">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}" class="hris-input">
                    </div>

                    <div class="hris-field">
                        <label class="hris-label">Employee</label>
                        <select name="employee" class="hris-select">
                            <option value="">All Employees</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ request('employee') == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hris-field">
                        <label class="hris-label">Department</label>
                        <select name="department" class="hris-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>
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
                        <a href="{{ route('reports.attendance') }}" class="hris-btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="hris-stat-grid">
            <div class="hris-stat-card">
                <p class="hris-stat-label">Present</p>
                <p class="hris-stat-value text-success">{{ $summary['present'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Absent</p>
                <p class="hris-stat-value text-danger">{{ $summary['absent'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Late</p>
                <p class="hris-stat-value text-warning">{{ $summary['late'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Timed Out</p>
                <p class="hris-stat-value text-info">{{ $summary['timed_out'] ?? 0 }}</p>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Attendance Distribution</h2>
                    <p class="hris-panel-subtitle">Visual breakdown of attendance status.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <div class="hris-chart-wrap">
                    <canvas id="attendanceChart" height="100"></canvas>
                </div>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Attendance Trend</h2>
                    <p class="hris-panel-subtitle">Selected period volume broken into day-by-day activity.</p>
                </div>
            </div>

            <div class="hris-panel-body d-grid gap-3">
                @php($trendMax = max(1, (int) collect($trend)->max('total')))
                @foreach($trend as $point)
                    <div class="d-grid gap-1">
                        <div class="d-flex justify-content-between small hris-muted">
                            <span>{{ $point['label'] }}</span>
                            <span>{{ $point['total'] }} logs</span>
                        </div>
                        <div class="hris-progress-track">
                            <div class="hris-progress-bar" style="width: {{ round(($point['total'] / $trendMax) * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        @if($attendances->count())
            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">Attendance Records</h2>
                        <p class="hris-panel-subtitle">Latest attendance entries with time in and time out details.</p>
                    </div>

                    <span class="hris-pill hris-pill-info">{{ $attendances->count() }} records</span>
                </div>

                <div class="hris-table-wrap">
                    <table class="hris-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Time In</th>
                                <th>Time Out</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $record)
                                <tr>
                                    <td>{{ $record->employee->first_name }} {{ $record->employee->last_name }}</td>
                                    <td>{{ $record->attendance_date->format('M d, Y') }}</td>
                                    <td>{{ $record->time_in?->format('H:i:s') ?? '-' }}</td>
                                    <td>{{ $record->time_out?->format('H:i:s') ?? '-' }}</td>
                                    <td>
                                        <span class="hris-pill {{ $record->time_out ? 'hris-pill-info' : ($record->status === 'present' ? 'hris-pill-success' : ($record->status === 'absent' ? 'hris-pill-danger' : 'hris-pill-warning')) }}">
                                            {{ $record->time_out ? 'Timed Out' : ucfirst($record->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            <div class="hris-empty">
                <div class="hris-empty-icon"><i class="bi bi-file-earmark-text"></i></div>
                <p>No attendance records available.</p>
            </div>
        @endif
    </div>

    <script>
        const ctx = document.getElementById('attendanceChart');
        if (ctx && window.Chart) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Present', 'Absent', 'Late', 'Timed Out'],
                    datasets: [{
                        data: [
                            {{ $summary['present'] ?? 0 }},
                            {{ $summary['absent'] ?? 0 }},
                            {{ $summary['late'] ?? 0 }},
                            {{ $summary['timed_out'] ?? 0 }}
                        ],
                        backgroundColor: ['#10b981', '#ef4444', '#f59e0b', '#0ea5e9'],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    cutout: '58%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#5b6776',
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: (context) => ` ${context.label}: ${context.parsed}`
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
