<x-app-layout>
    <x-slot name="title">Reports</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Insights</p>
                    <h1 class="hris-title">HR Reports</h1>
                    <p class="hris-subtitle">Review employee, attendance, leave, department, and activity snapshots from a single dashboard.</p>
                </div>

                <div class="hris-actions">
                    <span class="hris-pill hris-pill-info">Live overview</span>
                </div>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Quick Statistics</h2>
                    <p class="hris-panel-subtitle">A compact snapshot of the current workforce state.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <div class="hris-stat-grid">
                    <div class="hris-stat-card">
                        <p class="hris-stat-label">Total Employees</p>
                        <p class="hris-stat-value text-primary">{{ $stats['total_employees'] ?? 0 }}</p>
                    </div>

                    <div class="hris-stat-card">
                        <p class="hris-stat-label">Departments</p>
                        <p class="hris-stat-value text-success">{{ $stats['total_departments'] ?? 0 }}</p>
                    </div>

                    <div class="hris-stat-card">
                        <p class="hris-stat-label">Pending Leaves</p>
                        <p class="hris-stat-value text-warning">{{ $stats['pending_leaves'] ?? 0 }}</p>
                    </div>

                    <div class="hris-stat-card">
                        <p class="hris-stat-label">Present Today</p>
                        <p class="hris-stat-value text-info">{{ $stats['today_attendance'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </section>

        @php
            $exportReports = [
                ['label' => 'Employees', 'type' => 'employees', 'description' => 'Employee records with department and account details.'],
                ['label' => 'Attendance', 'type' => 'attendance', 'description' => 'Presence, absence, and time log records.'],
                ['label' => 'Leaves', 'type' => 'leaves', 'description' => 'Leave request history and approval status.'],
                ['label' => 'Departments', 'type' => 'departments', 'description' => 'Department coverage and staffing totals.'],
                ['label' => 'Activity', 'type' => 'activity', 'description' => 'Operational HR activity for the selected period.'],
            ];
        @endphp

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Export Center</h2>
                    <p class="hris-panel-subtitle">Generate CSV or PDF files for each report type.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <div class="hris-grid">
                    @foreach($exportReports as $report)
                        <div class="card card-soft h-100">
                            <div class="card-body d-grid gap-3">
                                <div>
                                    <div class="fw-semibold hris-text-dark">{{ $report['label'] }} Report</div>
                                    <div class="small hris-muted">{{ $report['description'] }}</div>
                                </div>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('reports.export', ['type' => $report['type'], 'format' => 'csv']) }}" class="hris-btn-secondary">CSV Export</a>
                                    <a href="{{ route('reports.export', ['type' => $report['type'], 'format' => 'pdf']) }}" class="hris-btn-primary">PDF Export</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Analytics Snapshot</h2>
                    <p class="hris-panel-subtitle">Attendance trends, time logs, and performance summaries update from live database queries.</p>
                </div>
            </div>

            <div class="hris-panel-body d-grid gap-4">
                <div class="row g-3">
                    <div class="col-12 col-xl-8">
                        <div class="hris-chart-card h-100">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <h3 class="h5 mb-1 hris-text-dark">Attendance Trends</h3>
                                    <p class="small hris-muted mb-0">Seven-day attendance volume with status breakdown.</p>
                                </div>
                                <span class="hris-pill hris-pill-info">7 days</span>
                            </div>

                            <div class="mt-3">
                                <canvas id="attendanceTrendChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-4">
                        <div class="hris-chart-card h-100">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <h3 class="h5 mb-1 hris-text-dark">Log Distribution</h3>
                                    <p class="small hris-muted mb-0">Daily, weekly, and monthly time logs.</p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <canvas id="timeLogChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-12 col-xl-7">
                        <div class="hris-soft-panel h-100">
                            <h3 class="h5 mb-1 hris-text-dark">Employee Performance Summary</h3>
                            <p class="small hris-muted">Based on attendance punctuality and work consistency in the last 30 days.</p>

                            <div class="mt-4 d-grid gap-3">
                                @foreach($employeePerformance as $employee)
                                    <div class="hris-surface p-4">
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                            <div>
                                                <p class="fw-semibold mb-1 hris-text-dark">{{ $employee['name'] }}</p>
                                                <p class="small hris-muted mb-0">{{ $employee['department'] }} • {{ $employee['attendance_count'] }} attendance logs</p>
                                            </div>
                                            <span class="hris-pill {{ $employee['score'] >= 90 ? 'hris-pill-success' : ($employee['score'] >= 75 ? 'hris-pill-warning' : 'hris-pill-danger') }}">
                                                {{ $employee['status'] }}
                                            </span>
                                        </div>

                                        <div class="mt-3 row g-2 small hris-muted">
                                            <div class="col-6 col-md-3">Present: <span class="text-success">{{ $employee['present_count'] }}</span></div>
                                            <div class="col-6 col-md-3">Late: <span class="text-warning">{{ $employee['late_count'] }}</span></div>
                                            <div class="col-6 col-md-3">Timed Out: <span class="text-info">{{ $employee['timed_out_count'] }}</span></div>
                                            <div class="col-6 col-md-3">Hours: <span class="text-primary">{{ $employee['hours_worked'] }}</span></div>
                                        </div>

                                        <div class="mt-3 hris-progress-track">
                                            <div class="hris-progress-bar" style="width: {{ $employee['score'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-5">
                        <div class="hris-chart-card h-100">
                            <h3 class="h5 mb-1 hris-text-dark">Work-Hour Analytics</h3>
                            <p class="small hris-muted">Employees with the highest tracked hours in the last 30 days.</p>

                            <div class="mt-3">
                                <canvas id="workHourChart" height="120"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Chart) {
                return;
            }

            const attendanceCanvas = document.getElementById('attendanceTrendChart');
            if (attendanceCanvas) {
                new Chart(attendanceCanvas, {
                    type: 'line',
                    data: {
                        labels: [
                            @foreach($attendanceTrend as $point)
                                @json($point['label']),
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Attendance logs',
                            data: [
                                @foreach($attendanceTrend as $point)
                                    {{ $point['total'] }},
                                @endforeach
                            ],
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.12)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointBackgroundColor: '#2563eb'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }

            const timeLogCanvas = document.getElementById('timeLogChart');
            if (timeLogCanvas) {
                new Chart(timeLogCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Daily', 'Weekly', 'Monthly'],
                        datasets: [{
                            data: [
                                {{ collect($dailyTimeLogs)->sum('total') }},
                                {{ collect($weeklyTimeLogs)->sum('total') }},
                                {{ collect($monthlyTimeLogs)->sum('total') }}
                            ],
                            backgroundColor: ['#2563eb', '#8b5cf6', '#10b981'],
                            borderColor: '#ffffff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '62%',
                        plugins: {
                            legend: { position: 'bottom' }
                        }
                    }
                });
            }

            const workHourCanvas = document.getElementById('workHourChart');
            if (workHourCanvas) {
                new Chart(workHourCanvas, {
                    type: 'bar',
                    data: {
                        labels: [
                            @foreach($workHourAnalytics as $record)
                                @json($record['name']),
                            @endforeach
                        ],
                        datasets: [{
                            label: 'Hours',
                            data: [
                                @foreach($workHourAnalytics as $record)
                                    {{ $record['hours'] }},
                                @endforeach
                            ],
                            backgroundColor: '#1b4fd6',
                            borderRadius: 8,
                            barThickness: 18
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: 'y',
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: { beginAtZero: true }
                        }
                    }
                });
            }
        });
    </script>

</x-app-layout>
