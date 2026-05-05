<x-app-layout>
    <x-slot name="title">Leave Requests Report</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Reports</p>
                    <h1 class="hris-title">Leave Requests Report</h1>
                    <p class="hris-subtitle">Monitor leave usage by status, request type, and time range.</p>
                </div>

                <a href="{{ route('reports.index') }}" class="hris-btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
            </div>
        </section>

        <section class="hris-stat-grid">
            <div class="hris-stat-card">
                <p class="hris-stat-label">Pending</p>
                <p class="hris-stat-value text-amber-300">{{ $summary['pending'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Approved</p>
                <p class="hris-stat-value text-emerald-300">{{ $summary['approved'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Rejected</p>
                <p class="hris-stat-value text-rose-300">{{ $summary['rejected'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Total</p>
                <p class="hris-stat-value text-sky-300">{{ $summary['total'] ?? 0 }}</p>
            </div>
        </section>

        @if($leaves->count())
            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">Leave Entries</h2>
                        <p class="hris-panel-subtitle">A summary of leave requests across the organization.</p>
                    </div>

                    <span class="hris-pill hris-pill-info">{{ $leaves->count() }} records</span>
                </div>

                <div class="hris-table-wrap">
                    <table class="hris-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Days</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leaves as $leave)
                                <tr>
                                    <td>{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</td>
                                    <td>{{ $leave->start_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->end_date->format('M d, Y') }}</td>
                                    <td>{{ $leave->end_date->diffInDays($leave->start_date) + 1 }}</td>
                                    <td>
                                        <span class="hris-pill {{ $leave->status === 'pending' ? 'hris-pill-warning' : ($leave->status === 'approved' ? 'hris-pill-success' : 'hris-pill-danger') }}">
                                            {{ ucfirst($leave->status) }}
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
                <div class="hris-empty-icon"><i class="bi bi-calendar2-check"></i></div>
                <p>No leave records available.</p>
            </div>
        @endif
    </div>
</x-app-layout>
