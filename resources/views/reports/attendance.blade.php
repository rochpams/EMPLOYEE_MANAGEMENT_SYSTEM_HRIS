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

                <a href="{{ route('reports.index') }}" class="hris-btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
            </div>
        </section>

        <section class="hris-stat-grid">
            <div class="hris-stat-card">
                <p class="hris-stat-label">Present</p>
                <p class="hris-stat-value text-emerald-300">{{ $summary['present'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Absent</p>
                <p class="hris-stat-value text-rose-300">{{ $summary['absent'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Late</p>
                <p class="hris-stat-value text-amber-300">{{ $summary['late'] ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">On Leave</p>
                <p class="hris-stat-value text-sky-300">{{ $summary['on_leave'] ?? 0 }}</p>
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
                                        <span class="hris-pill {{ $record->status === 'present' ? 'hris-pill-success' : ($record->status === 'absent' ? 'hris-pill-danger' : 'hris-pill-warning') }}">
                                            {{ ucfirst($record->status) }}
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
</x-app-layout>
