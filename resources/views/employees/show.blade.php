<x-app-layout>
    <x-slot name="title">Employee Details - {{ $employee->first_name }} {{ $employee->last_name }}</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Employee Profile</p>
                    <h1 class="hris-title">{{ $employee->full_name }}</h1>
                    <p class="hris-subtitle">Detailed employee record with department, position, and employment status.</p>
                </div>

                <div class="hris-actions">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
                        <a href="{{ route('employees.edit', $employee) }}" class="hris-btn-primary">Edit Employee</a>
                    @endif
                    <a href="{{ route('employees.index') }}" class="hris-btn-secondary">Back</a>
                </div>
            </div>
        </section>

        <section class="hris-panel">
            <div class="hris-panel-body">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">First Name</div>
                                <div class="fw-semibold mt-2">{{ $employee->first_name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Last Name</div>
                                <div class="fw-semibold mt-2">{{ $employee->last_name }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Email</div>
                                <div class="fw-semibold mt-2">{{ $employee->email }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Phone</div>
                                <div class="fw-semibold mt-2">{{ $employee->phone ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Department</div>
                                <div class="fw-semibold mt-2">{{ $employee->department->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Position</div>
                                <div class="fw-semibold mt-2">{{ $employee->position }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Hire Date</div>
                                <div class="fw-semibold mt-2">{{ $employee->hire_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card card-soft h-100">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Employment Status</div>
                                <div class="mt-2">
                                    <span class="hris-pill {{ $employee->employment_status === 'active' ? 'hris-pill-success' : 'hris-pill-danger' }}">{{ ucfirst($employee->employment_status) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card card-soft">
                            <div class="card-body">
                                <div class="small text-uppercase text-secondary">Address</div>
                                <div class="fw-semibold mt-2">{{ $employee->address ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>
