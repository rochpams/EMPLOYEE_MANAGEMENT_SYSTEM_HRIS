<x-app-layout>
    <x-slot name="title">Employee List Report</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Reports</p>
                    <h1 class="hris-title">Employee List Report</h1>
                    <p class="hris-subtitle">Browse employee records with contact details, department assignment, position, and employment status.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('reports.export', ['type' => 'employees', 'format' => 'csv', 'department' => request('department'), 'status' => request('status')]) }}" class="hris-btn-secondary">CSV Export</a>
                    <a href="{{ route('reports.export', ['type' => 'employees', 'format' => 'pdf', 'department' => request('department'), 'status' => request('status')]) }}" class="hris-btn-primary">PDF Export</a>
                    <a href="{{ route('reports.index') }}" class="hris-btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
                </div>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Employee Status Distribution</h2>
                    <p class="hris-panel-subtitle">Visual breakdown of employees by employment status.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <div class="hris-chart-wrap">
                    <canvas id="employeeStatusChart" height="100"></canvas>
                </div>
            </div>
        </section>

        @if($employees->count())
            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">Employee Directory</h2>
                        <p class="hris-panel-subtitle">Current employee records pulled from the HRIS database.</p>
                    </div>

                    <span class="hris-pill hris-pill-info">{{ $employees->count() }} employees</span>
                </div>

                <div class="hris-table-wrap">
                    <table class="hris-table">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $emp)
                                <tr>
                                    <td>{{ $emp->id }}</td>
                                    <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                                    <td>{{ $emp->email }}</td>
                                    <td>{{ $emp->department->name ?? 'N/A' }}</td>
                                    <td>{{ $emp->position }}</td>
                                    <td>
                                        <span class="hris-pill {{ $emp->employment_status === 'active' ? 'hris-pill-success' : 'hris-pill-danger' }}">
                                            {{ ucfirst($emp->employment_status) }}
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
                <div class="hris-empty-icon"><i class="bi bi-people"></i></div>
                <p>No employee data available.</p>
            </div>
        @endif
    </div>

    <script>
        @php
            $statusCounts = [];
            foreach($employees as $emp) {
                $status = ucfirst($emp->employment_status);
                $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
            }
        @endphp
        
        const ctx = document.getElementById('employeeStatusChart');
        if (ctx && window.Chart) {
            const labels = [
                @foreach($statusCounts as $status => $count)
                    '{{ $status }}',
                @endforeach
            ];
            const data = [
                @foreach($statusCounts as $status => $count)
                    {{ $count }},
                @endforeach
            ];
            const palette = ['#2563eb', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'];

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: labels.map((_, i) => palette[i % palette.length]),
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
                                label: (context) => ` ${context.label}: ${context.parsed} employees`
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
