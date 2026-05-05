<x-app-layout>
    <x-slot name="title">Department Details</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Department Profile</p>
                    <h1 class="hris-title">{{ $department->name }}</h1>
                    <p class="hris-subtitle">View ownership, staffing, and the current department status.</p>
                </div>

                <div class="hris-actions">
                    <a href="{{ route('departments.edit', $department) }}" class="hris-btn-primary">Edit Department</a>
                    <a href="{{ route('departments.index') }}" class="hris-btn-secondary">Back</a>
                </div>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-body">
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Status</div>
                                <div class="mt-2">
                                    <span class="hris-pill {{ $department->status === 'active' ? 'hris-pill-success' : 'hris-pill-neutral' }}">{{ ucfirst($department->status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Employees</div>
                                <div class="h4 mb-0 mt-2 text-primary">{{ $department->employees->count() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-soft">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Manager</div>
                                <div class="fw-semibold mt-2">{{ $department->manager?->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-soft mb-3">
                    <div class="card-body">
                        <div class="small text-uppercase text-secondary">Description</div>
                        <div class="mt-2">{{ $department->description ?? 'No description provided' }}</div>
                    </div>
                </div>

                <div>
                    <p class="small text-uppercase text-secondary mb-3">Assigned Employees</p>
                    @if($department->employees->count())
                        <div class="row g-3">
                            @foreach($department->employees as $employee)
                                <div class="col-12 col-md-6">
                                    <div class="card card-soft h-100">
                                        <div class="card-body">
                                            <div class="fw-semibold">{{ $employee->full_name }}</div>
                                            <div class="text-secondary small">{{ $employee->position }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="hris-empty">
                            <div class="hris-empty-icon"><i class="bi bi-diagram-3"></i></div>
                            <p>No employees assigned.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
