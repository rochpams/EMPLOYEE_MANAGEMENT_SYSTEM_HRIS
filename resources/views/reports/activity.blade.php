<x-app-layout>
    <x-slot name="title">Activity Report</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Reports</p>
                    <h1 class="hris-title">Activity Report</h1>
                    <p class="hris-subtitle">Monitor the latest HR events, approvals, and employee onboarding activity.</p>
                </div>

                <a href="{{ route('reports.index') }}" class="hris-btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
            </div>
        </section>

        <section class="hris-stat-grid">
            <div class="hris-stat-card">
                <p class="hris-stat-label">Today Check-ins</p>
                <p class="hris-stat-value text-sky-300">{{ $stats['today_checkins'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Pending Approvals</p>
                <p class="hris-stat-value text-amber-300">{{ $stats['pending_approvals'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Approved Today</p>
                <p class="hris-stat-value text-emerald-300">{{ $stats['approved_today'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">New Employees</p>
                <p class="hris-stat-value text-violet-300">{{ $stats['new_employees'] ?? 0 }}</p>
            </div>
        </section>

        @if(count($activities))
            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">Activity Log</h2>
                        <p class="hris-panel-subtitle">Recent events captured by the system.</p>
                    </div>

                    <span class="hris-pill hris-pill-info">{{ count($activities) }} events</span>
                </div>

                <div class="hris-panel-body d-grid gap-3">
                    @foreach($activities as $activity)
                        <div class="card card-soft">
                            <div class="card-body d-flex flex-column flex-sm-row justify-content-between gap-2">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold">{{ $activity['title'] }}</div>
                                    <div class="small text-secondary">{{ $activity['description'] }}</div>
                                </div>
                                <span class="small text-secondary text-uppercase">{{ $activity['time'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @else
            <div class="hris-empty">
                <div class="hris-empty-icon"><i class="bi bi-bar-chart-line"></i></div>
                <p>No activity records available.</p>
            </div>
        @endif
    </div>
</x-app-layout>
