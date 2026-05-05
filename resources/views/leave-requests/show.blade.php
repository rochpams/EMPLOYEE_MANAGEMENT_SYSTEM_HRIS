<x-app-layout>
    <x-slot name="title">Leave Request Details</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Leave Request</p>
                    <h1 class="hris-title">{{ $leaveRequest->employee?->full_name ?? 'Unknown employee' }}</h1>
                    <p class="hris-subtitle">Detailed request summary with approval state and requested leave dates.</p>
                </div>

                <a href="{{ route('leave-requests.index') }}" class="hris-btn-secondary">Back</a>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Status</div>
                                <div class="mt-2"><span class="hris-pill {{ $leaveRequest->status === 'pending' ? 'hris-pill-warning' : ($leaveRequest->status === 'approved' ? 'hris-pill-success' : 'hris-pill-danger') }}">{{ ucfirst($leaveRequest->status) }}</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Approved By</div>
                                <div class="fw-semibold mt-2">{{ $leaveRequest->approvedBy?->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Leave Type</div>
                                <div class="fw-semibold mt-2">{{ ucfirst(str_replace('_', ' ', $leaveRequest->leave_type)) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Employee</div>
                                <div class="fw-semibold mt-2">{{ $leaveRequest->employee?->full_name ?? 'Unknown' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Start Date</div>
                                <div class="fw-semibold mt-2">{{ $leaveRequest->start_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">End Date</div>
                                <div class="fw-semibold mt-2">{{ $leaveRequest->end_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-soft">
                    <div class="card-body">
                        <div class="small text-uppercase text-secondary">Reason</div>
                        <div class="mt-2">{{ $leaveRequest->reason }}</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
