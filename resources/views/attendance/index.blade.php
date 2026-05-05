<x-app-layout>
    <x-slot name="title">Attendance</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Attendance</p>
                    <h1 class="hris-title">My Attendance</h1>
                    <p class="hris-subtitle">Track attendance activity, filter records, and record time in or time out from the same page.</p>
                </div>

                <span class="hris-pill hris-pill-info">{{ $totalEmployees ?? count($employees) }} employees</span>
            </div>
        </section>

        <section class="hris-stat-grid">
            <div class="hris-stat-card">
                <p class="hris-stat-label">Total Employees</p>
                <p class="hris-stat-value text-sky-300">{{ $totalEmployees ?? count($employees) }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Present Today</p>
                <p class="hris-stat-value text-emerald-300">{{ $presentToday ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Late Arrivals</p>
                <p class="hris-stat-value text-amber-300">{{ $lateArrivals ?? 0 }}</p>
            </div>
            <div class="hris-stat-card">
                <p class="hris-stat-label">Absent</p>
                <p class="hris-stat-value text-rose-300">{{ $absent ?? 0 }}</p>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Filter Attendance</h2>
                    <p class="hris-panel-subtitle">Narrow the list by date, employee, or status.</p>
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
                        <label class="hris-label">Status</label>
                        <select name="status" class="hris-select">
                            <option value="">All Statuses</option>
                            <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                            <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                            <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                        </select>
                    </div>

                    <div class="md:col-span-3 hris-form-actions">
                        <button type="submit" class="hris-btn-primary">Apply Filters</button>
                        <a href="{{ route('attendance.index') }}" class="hris-btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </section>

        @if(auth()->user()->role === 'employee' || auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
            <section class="hris-panel">
                <div class="hris-panel-header">
                    <div>
                        <h2 class="hris-panel-title">Record Attendance</h2>
                        <p class="hris-panel-subtitle">Pick an employee once and reuse it for both actions.</p>
                    </div>
                </div>

                <div class="hris-panel-body">
                    <div class="hris-field mb-5">
                        <label class="hris-label" for="employeeSelect">Employee</label>
                        <select id="employeeSelect" class="hris-select">
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <form action="{{ route('attendance.time-in') }}" method="POST">
                            @csrf
                            <input type="hidden" name="employee_id" id="empIdIn">
                            <button type="submit" class="hris-btn-primary w-full">Time In</button>
                        </form>

                        <form action="{{ route('attendance.time-out') }}" method="POST">
                            @csrf
                            <input type="hidden" name="employee_id" id="empIdOut">
                            <button type="submit" class="hris-btn-secondary w-full border-rose-500/40 text-rose-200 hover:border-rose-400 hover:text-white">Time Out</button>
                        </form>
                    </div>
                </div>
            </section>
        @endif

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Attendance Records</h2>
                    <p class="hris-panel-subtitle">Latest attendance activity with department context.</p>
                </div>

                <span class="hris-pill hris-pill-info">
                    {{ $attendances->count() }} {{ $attendances->count() === 1 ? 'record' : 'records' }}
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
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($attendances->count())
                            @foreach($attendances as $record)
                                <tr>
                                    <td>{{ $record->attendance_date->format('m/d/Y') }}</td>
                                    <td>{{ $record->employee?->first_name }} {{ $record->employee?->last_name }}</td>
                                    <td>{{ $record->employee?->department?->name ?? '-' }}</td>
                                    <td>{{ $record->time_in?->format('H:i') ?? '-' }}</td>
                                    <td>{{ $record->time_out?->format('H:i') ?? '-' }}</td>
                                    <td>
                                        @if($record->status === 'present')
                                            <span class="hris-pill hris-pill-success">Present</span>
                                        @elseif($record->status === 'absent')
                                            <span class="hris-pill hris-pill-danger">Absent</span>
                                        @elseif($record->status === 'late')
                                            <span class="hris-pill hris-pill-warning">Late</span>
                                        @else
                                            <span class="hris-pill hris-pill-neutral">{{ ucfirst($record->status) }}</span>
                                        @endif
                                    </td>
                                    <td>-</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7">
                                    <div class="hris-empty">
                                        <div class="hris-empty-icon"><i class="bi bi-file-earmark-text"></i></div>
                                        <p>No attendance records found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-700/60 px-6 py-4 text-sm text-slate-400">
                Showing {{ $attendances->count() }} attendance {{ $attendances->count() === 1 ? 'record' : 'records' }}
            </div>
        </section>
    </div>

    <script>
        const employeeSelect = document.getElementById('employeeSelect');
        const empIdIn = document.getElementById('empIdIn');
        const empIdOut = document.getElementById('empIdOut');

        if (employeeSelect && empIdIn && empIdOut) {
            employeeSelect.addEventListener('change', function () {
                empIdIn.value = this.value;
                empIdOut.value = this.value;
            });
        }
    </script>
</x-app-layout>
