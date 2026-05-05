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

        <section class="hris-grid">
            <a href="{{ route('reports.show', 'employees') }}" class="hris-card">
                <div class="hris-card-icon"><i class="bi bi-people"></i></div>
                <h3 class="hris-card-title">Employee List Report</h3>
                <p class="hris-card-text">View the complete employee directory with departments, positions, and status.</p>
            </a>

            <a href="{{ route('reports.show', 'attendance') }}" class="hris-card">
                <div class="hris-card-icon">✓</div>
                <h3 class="hris-card-title">Attendance Report</h3>
                <p class="hris-card-text">Track attendance summaries and time logs by employee.</p>
            </a>

            <a href="{{ route('reports.show', 'leaves') }}" class="hris-card">
                <div class="hris-card-icon"><i class="bi bi-calendar2-check"></i></div>
                <h3 class="hris-card-title">Leave Request Report</h3>
                <p class="hris-card-text">Summarize leave requests by status, type, and approval state.</p>
            </a>

            <a href="{{ route('reports.show', 'departments') }}" class="hris-card">
                <div class="hris-card-icon"><i class="bi bi-diagram-3"></i></div>
                <h3 class="hris-card-title">Department Report</h3>
                <p class="hris-card-text">See department structure, manager ownership, and employee counts.</p>
            </a>

            <a href="{{ route('reports.show', 'activity') }}" class="hris-card">
                <div class="hris-card-icon"><i class="bi bi-bar-chart-line"></i></div>
                <h3 class="hris-card-title">Activity Report</h3>
                <p class="hris-card-text">Review recent HR activity and key operational events.</p>
            </a>
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
                        <p class="hris-stat-value text-sky-300">{{ $stats['total_employees'] ?? 0 }}</p>
                    </div>

                    <div class="hris-stat-card">
                        <p class="hris-stat-label">Departments</p>
                        <p class="hris-stat-value text-emerald-300">{{ $stats['total_departments'] ?? 0 }}</p>
                    </div>

                    <div class="hris-stat-card">
                        <p class="hris-stat-label">Pending Leaves</p>
                        <p class="hris-stat-value text-amber-300">{{ $stats['pending_leaves'] ?? 0 }}</p>
                    </div>

                    <div class="hris-stat-card">
                        <p class="hris-stat-label">Present Today</p>
                        <p class="hris-stat-value text-violet-300">{{ $stats['today_attendance'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
