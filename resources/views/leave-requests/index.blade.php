<x-app-layout>
    <x-slot name="title">Leave Requests</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Employee Requests</p>
                    <h1 class="hris-title">My Leave</h1>
                    <p class="hris-subtitle">Review submitted leave requests, their status, and supporting details in one place.</p>
                </div>

                @if(auth()->user()->role === 'employee')
                    <a href="{{ route('leave-requests.create') }}" class="hris-btn-primary">+ Submit Leave Request</a>
                @endif
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-header">
                <div>
                    <h2 class="hris-panel-title">Filter by Status</h2>
                    <p class="hris-panel-subtitle">Switch between all requests and individual approval states.</p>
                </div>
            </div>

            <div class="hris-panel-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('leave-requests.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
                    <a href="{{ route('leave-requests.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}">Pending</a>
                    <a href="{{ route('leave-requests.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-outline-secondary' }}">Approved</a>
                    <a href="{{ route('leave-requests.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-outline-secondary' }}">Rejected</a>
                    <a href="{{ route('leave-requests.index', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-dark' : 'btn-outline-secondary' }}">Cancelled</a>
                </div>
            </div>
        </section>

        @if($leaveRequests instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator ? $leaveRequests->count() : count($leaveRequests))
            <div class="d-grid gap-3">
                @foreach($leaveRequests as $leave)
                    <article class="hris-card">
                        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3">
                            <div class="flex-grow-1">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <h3 class="h5 mb-0">{{ $leave->employee?->full_name ?? 'Unknown employee' }}</h3>
                                    <span class="hris-pill {{ $leave->status === 'pending' ? 'hris-pill-warning' : ($leave->status === 'approved' ? 'hris-pill-success' : ($leave->status === 'cancelled' ? 'hris-pill-neutral' : 'hris-pill-danger')) }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="small text-uppercase text-secondary">Leave Type</div>
                                        <div>{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</div>
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="small text-uppercase text-secondary">Dates</div>
                                        <div>{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}</div>
                                    </div>

                                    <div class="col-12 col-md-6 col-xl-4">
                                        <div class="small text-uppercase text-secondary">Approved By</div>
                                        <div>{{ $leave->approvedBy?->name ?? 'N/A' }}</div>
                                    </div>
                                </div>

                                <div class="mt-3 p-3 border rounded bg-light">
                                    <span class="fw-semibold">Reason:</span> {{ $leave->reason }}
                                </div>
                            </div>

                            <div class="d-flex flex-column align-items-start align-items-lg-end gap-2">
                                @if(auth()->user()->isManager())
                                    @if($leave->status === 'pending')
                                        <div class="d-flex flex-wrap gap-2">
                                            <form action="{{ route('leave-requests.update-status', $leave) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                            </form>

                                            <form action="{{ route('leave-requests.update-status', $leave) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                            </form>
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('leave-requests.show', $leave) }}" class="btn btn-sm btn-outline-secondary">Review Details</a>
                                @endif

                                @if(auth()->user()->isEmployee() && $leave->status === 'pending')
                                    <form action="{{ route('leave-requests.cancel', $leave) }}" method="POST" onsubmit="return confirm('Cancel this request?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Cancel Request</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ method_exists($leaveRequests, 'links') ? $leaveRequests->links() : '' }}
            </div>
        @else
            <div class="hris-empty">
                <div class="hris-empty-icon"><i class="bi bi-calendar2-check"></i></div>
                <p>No leave requests found.</p>
            </div>
        @endif
    </div>
</x-app-layout>
