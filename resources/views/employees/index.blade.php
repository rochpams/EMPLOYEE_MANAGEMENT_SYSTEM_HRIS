@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Employees</h1>
        <p class="page-subtitle">Manage employee records and information</p>
    </div>
    <button class="btn-add" onclick="openAddModal()">
        + Add Employee
    </button>
</div>

<!-- Search & Filter Section -->
<div class="filter-section">
    <div class="search-box">
        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <circle cx="11" cy="11" r="8"></circle>
            <path d="m21 21-4.35-4.35"></path>
        </svg>
        <input type="text" id="searchInput" placeholder="Search employees..." class="search-input" onkeyup="filterTable()">
    </div>
    <div class="filter-group">
        <select id="departmentFilter" class="filter-select" onchange="filterTable()">
            <option value="">All Departments</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
        </select>
        <select id="statusFilter" class="filter-select" onchange="filterTable()">
            <option value="">All Statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="terminated">Terminated</option>
        </select>
    </div>
</div>

<!-- Employees Table -->
<div class="table-container">
    <table class="employees-table">
        <thead>
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Department</th>
                <th>Status</th>
                <th>Actions</th>
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
                    <span class="status-badge status-{{ strtolower($employee->employment_status) }}">
                        {{ ucfirst($employee->employment_status) }}
                    </span>
                </td>
                <td class="action-buttons">
                    <a href="{{ route('employees.show', $employee->id) }}" class="action-btn" title="View">👁️</a>
                    <button onclick="editEmployee({{ $employee->id }})" class="action-btn" title="Edit">✏️</button>
                    <form method="POST" action="{{ route('employees.destroy', $employee->id) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete" title="Delete" onclick="return confirm('Are you sure?')">🗑️</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="no-data">No employees found</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Add Employee Modal -->
<div id="addEmployeeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Add New Employee</h2>
            <button class="modal-close" onclick="closeAddModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('employees.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">First Name <span class="required">*</span></label>
                    <input type="text" name="first_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name <span class="required">*</span></label>
                    <input type="text" name="last_name" class="form-input" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Phone <span class="required">*</span></label>
                    <input type="tel" name="phone" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Address <span class="required">*</span></label>
                <input type="text" name="address" class="form-input" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Position <span class="required">*</span></label>
                    <input type="text" name="position" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Department <span class="required">*</span></label>
                    <select name="department_id" class="form-select" required>
                        <option value="">Select department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Hire Date <span class="required">*</span></label>
                    <input type="date" name="hire_date" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Employment Status <span class="required">*</span></label>
                    <select name="employment_status" class="form-select" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="terminated">Terminated</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Employee</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('addEmployeeModal').classList.add('show');
    }

    function closeAddModal() {
        document.getElementById('addEmployeeModal').classList.remove('show');
    }

    function filterTable() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const deptFilter = document.getElementById('departmentFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.employee-row');

        rows.forEach(row => {
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

    window.onclick = function(event) {
        const modal = document.getElementById('addEmployeeModal');
        if (event.target === modal) {
            closeAddModal();
        }
    }
</script>
@endsection
