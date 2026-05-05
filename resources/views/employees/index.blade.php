@extends('layouts.app')

@section('title', 'Employees')
@section('subtitle', 'Manage employee records and profile details.')

@section('content')
<div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
    <form class="d-flex gap-2 flex-wrap" role="search" aria-label="Filter employees" onsubmit="event.preventDefault(); filterTable();">
        <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="search" id="searchInput" class="form-control" placeholder="Search employees" onkeyup="filterTable()" aria-label="Search employees">
        </div>

        <select id="departmentFilter" class="form-select" onchange="filterTable()" aria-label="Filter by department">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
        </select>

        <select id="statusFilter" class="form-select" onchange="filterTable()" aria-label="Filter by employment status">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="terminated">Terminated</option>
        </select>
    </form>

    <a href="{{ route('employees.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus me-1" aria-hidden="true"></i>
        Add Employee
    </a>
</div>

<div class="card card-soft">
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0" id="employeesTable">
            <thead class="table-light">
                <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="employeesTableBody">
                @forelse($employees as $employee)
                <tr class="employee-row" data-dept="{{ $employee->department_id }}" data-status="{{ strtolower($employee->employment_status) }}" data-search="{{ strtolower($employee->first_name . ' ' . $employee->last_name . ' ' . $employee->email) }}">
                    <td>{{ $employee->id }}</td>
                    <td>{{ $employee->first_name }} {{ $employee->last_name }}</td>
                    <td>{{ $employee->email }}</td>
                    <td>{{ $employee->position }}</td>
                    <td>{{ $employee->department->name ?? 'N/A' }}</td>
                    <td>
                        <span class="status-badge status-{{ strtolower($employee->employment_status) }}">{{ ucfirst($employee->employment_status) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="table-actions">
                            <a href="{{ route('employees.show', $employee->id) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="View" aria-label="View {{ $employee->first_name }} {{ $employee->last_name }}">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-primary btn-icon" title="Edit" aria-label="Edit {{ $employee->first_name }} {{ $employee->last_name }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-icon" title="Delete" aria-label="Delete {{ $employee->first_name }} {{ $employee->last_name }}" onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-secondary">No employees found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
function filterTable() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const deptFilter = document.getElementById('departmentFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.employee-row');

    rows.forEach((row) => {
        let show = true;

        if (searchInput && !row.getAttribute('data-search').includes(searchInput)) {
            show = false;
        }

        if (deptFilter && row.getAttribute('data-dept') !== deptFilter) {
            show = false;
        }

        if (statusFilter && row.getAttribute('data-status') !== statusFilter.toLowerCase()) {
            show = false;
        }

        row.style.display = show ? '' : 'none';
    });
}
</script>
@endsection
