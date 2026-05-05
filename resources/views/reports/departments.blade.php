<x-app-layout>
    <x-slot name="title">Department Report</x-slot>

    <div class="hris-shell">
        <section class="hris-hero">
            <div class="hris-hero-grid">
                <div>
                    <p class="hris-eyebrow">Reports</p>
                    <h1 class="hris-title">Department Report</h1>
                    <p class="hris-subtitle">View each department with its manager, status, and assigned employees.</p>
                </div>

                <a href="{{ route('reports.index') }}" class="hris-btn-secondary"><i class="bi bi-arrow-left me-1"></i>Back to Reports</a>
            </div>
        </section>

        @if($departments->count())
            <div class="d-grid gap-3">
                @foreach($departments as $dept)
                    <section class="hris-panel">
                        <div class="hris-panel-header">
                            <div>
                                <h2 class="hris-panel-title">{{ $dept->name }}</h2>
                                <p class="hris-panel-subtitle">{{ $dept->description ?? 'No description' }}</p>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="hris-pill hris-pill-info">{{ $dept->employees->count() }} employees</span>
                                <span class="hris-pill {{ $dept->status === 'active' ? 'hris-pill-success' : 'hris-pill-neutral' }}">{{ ucfirst($dept->status) }}</span>
                            </div>
                        </div>

                        <div class="hris-panel-body">
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <div class="card card-soft h-100">
                                        <div class="card-body">
                                            <div class="small text-uppercase text-secondary">Manager</div>
                                            <div class="mt-2">{{ $dept->manager->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card card-soft h-100">
                                        <div class="card-body">
                                            <div class="small text-uppercase text-secondary">Employee Count</div>
                                            <div class="h4 mb-0 mt-2 text-primary">{{ $dept->employees->count() }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($dept->employees->count())
                                <div class="hris-table-wrap">
                                    <table class="hris-table">
                                        <thead>
                                            <tr>
                                                <th>Employee</th>
                                                <th>Position</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($dept->employees as $emp)
                                                <tr>
                                                    <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
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
                            @endif
                        </div>
                    </section>
                @endforeach
            </div>
        @else
            <div class="hris-empty">
                <div class="hris-empty-icon"><i class="bi bi-diagram-3"></i></div>
                <p>No department data available.</p>
            </div>
        @endif
    </div>
</x-app-layout>
