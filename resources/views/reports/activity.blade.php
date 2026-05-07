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
                <p class="hris-stat-value text-primary">{{ $stats['today_checkins'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Pending Approvals</p>
                <p class="hris-stat-value text-warning">{{ $stats['pending_approvals'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Approved Today</p>
                <p class="hris-stat-value text-success">{{ $stats['approved_today'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">New Employees</p>
                <p class="hris-stat-value text-info">{{ $stats['new_employees'] ?? 0 }}</p>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Activity Statistics</h2>
                    <p class="hris-panel-subtitle">Visual breakdown of recent activities by type.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <canvas id="activityChart" height="80"></canvas>
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

    <script>
        const ctx = document.getElementById('activityChart');
        if (ctx && window.Chart) {
            const activityChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Today Check-ins', 'Pending Approvals', 'Approved Today', 'New Employees'],
                    datasets: [{
                        label: 'Count',
                        data: [
                            {{ $stats['today_checkins'] ?? 0 }},
                            {{ $stats['pending_approvals'] ?? 0 }},
                            {{ $stats['approved_today'] ?? 0 }},
                            {{ $stats['new_employees'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#2563eb',
                            '#f59e0b',
                            '#10b981',
                            '#8b5cf6'
                        ],
                        borderRadius: 8,
                        barThickness: 18
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            labels: {
                                color: '#5b6776',
                                font: { size: 12 }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                color: '#5b6776'
                            },
                            grid: {
                                color: 'rgba(91, 103, 118, 0.15)'
                            }
                        },
                        y: {
                            ticks: {
                                color: '#162033'
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
