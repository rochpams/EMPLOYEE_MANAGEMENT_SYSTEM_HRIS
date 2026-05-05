<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="subtitle">
        @if(auth()->user()->isAdmin())
            Admin overview
        @elseif(auth()->user()->isHR())
            HR operations overview
        @elseif(auth()->user()->isManager())
            Team performance overview
        @else
            Personal attendance and leave overview
        @endif
    </x-slot>

    <div class="row g-3 mb-4">
        @if(auth()->user()->isAdmin() || auth()->user()->isHR())
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100">
                    <div class="card-body">
                        <div class="text-secondary small mb-2">Total Employees</div>
                        <div class="h2 mb-0">{{ $totalEmployees ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100">
                    <div class="card-body">
                        <div class="text-secondary small mb-2">Departments</div>
                        <div class="h2 mb-0">{{ $totalDepartments ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100">
                    <div class="card-body">
                        <div class="text-secondary small mb-2">Present Today</div>
                        <div class="h2 mb-0 text-success">{{ $presentToday ?? 0 }}</div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100">
                    <div class="card-body">
                        <div class="text-secondary small mb-2">Pending Leaves</div>
                        <div class="h2 mb-0 text-warning">{{ $pendingLeaveRequests ?? 0 }}</div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100"><div class="card-body"><div class="text-secondary small mb-2">My Attendance</div><div class="h2 mb-0">{{ $attendanceCount ?? 0 }}</div></div></div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100"><div class="card-body"><div class="text-secondary small mb-2">My Leave Requests</div><div class="h2 mb-0">{{ $pendingLeaveRequests ?? 0 }}</div></div></div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100"><div class="card-body"><div class="text-secondary small mb-2">This Month</div><div class="h2 mb-0">{{ $attendanceCount ?? 0 }}</div></div></div>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <div class="card card-soft h-100"><div class="card-body"><div class="text-secondary small mb-2">Leave Balance</div><div class="h2 mb-0">{{ $leaveBalance ?? 0 }}</div></div></div>
            </div>
        @endif
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-7">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <h2 class="h5 mb-1">Recent Activity</h2>
                    <p class="text-secondary small mb-3">Latest HR system updates</p>

                    @if(isset($recentActivity) && count($recentActivity) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivity as $activity)
                                @php
                                    $isArray = is_array($activity);
                                    $title = $isArray ? ($activity['title'] ?? 'Activity') : 'Leave Request';
                                    $description = $isArray ? ($activity['description'] ?? '') : ucfirst($activity->status) . ' leave request';
                                    $time = $isArray ? ($activity['time'] ?? '') : ($activity->created_at?->diffForHumans() ?? '');
                                @endphp
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between gap-2">
                                        <strong>{{ $title }}</strong>
                                        <small class="text-secondary">{{ $time }}</small>
                                    </div>
                                    <div class="small text-secondary">{{ $description }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-secondary mb-0">No recent activity.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-5">
            <div class="card card-soft h-100">
                <div class="card-body">
                    <h2 class="h5 mb-1">Upcoming Leaves</h2>
                    <p class="text-secondary small mb-3">Scheduled approved leave periods</p>

                    @if(isset($upcomingLeaves) && count($upcomingLeaves) > 0)
                        <div class="list-group list-group-flush">
                            @foreach($upcomingLeaves as $leave)
                                <div class="list-group-item px-0">
                                    <div class="fw-semibold">{{ $leave['employee_name'] }}</div>
                                    <div class="small text-secondary">{{ $leave['leave_type'] }} - {{ $leave['date_range'] }}</div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-secondary mb-0">No upcoming approved leaves.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
