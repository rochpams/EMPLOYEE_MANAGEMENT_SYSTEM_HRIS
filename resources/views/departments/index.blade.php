@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Departments</h1>
        <p class="page-subtitle">Manage organizational departments</p>
    </div>
    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
        <button class="btn-add" onclick="openCreateModal()">
            <span>+</span> Add Department
        </button>
    @endif
</div>

<!-- Departments Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px;">
    @forelse($departments as $dept)
        <div style="background: rgba(30, 41, 59, 0.6); border: 1px solid #334155; border-radius: 12px; padding: 24px; transition: all 0.3s ease;">
            <!-- Header with Status -->
            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
                <h3 style="font-size: 18px; font-weight: 700; color: #fff;">{{ $dept->name }}</h3>
                <span class="status-badge status-{{ $dept->status === 'active' ? 'active' : 'inactive' }}">
                    {{ ucfirst($dept->status) }}
                </span>
            </div>

            <!-- Description -->
            <p style="font-size: 14px; color: #94a3b8; margin-bottom: 16px; line-height: 1.5;">
                {{ $dept->description ?? 'No description provided' }}
            </p>

            <!-- Employee Count -->
            <div style="display: flex; align-items: center; gap: 6px; color: #cbd5e1; font-size: 14px; margin-bottom: 16px;">
                <span>{{ $dept->employees->count() }} employee{{ $dept->employees->count() !== 1 ? 's' : '' }}</span>
            </div>

            <!-- Manager Info -->
            <div style="background: rgba(15, 23, 42, 0.5); padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <p style="font-size: 12px; color: #94a3b8; margin-bottom: 4px;">Manager</p>
                <p style="font-size: 14px; color: #e2e8f0; font-weight: 500;">
                    {{ $dept->manager->name ?? 'No manager assigned' }}
                </p>
            </div>

            <!-- Action Buttons -->
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
                <div style="display: flex; gap: 8px;">
                    <button onclick="editDepartment({{ $dept->id }})" style="flex: 1; padding: 8px 16px; background: rgba(59, 130, 246, 0.1); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.3); border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.3s ease;">
                        Edit
                    </button>
                    <form action="{{ route('departments.destroy', $dept) }}" method="POST" style="flex: 1; margin: 0;" onsubmit="return confirm('Delete this department?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="width: 100%; padding: 8px 16px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px; transition: all 0.3s ease;">
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
    @empty
        <div style="grid-column: 1 / -1; text-align: center; padding: 48px 16px; color: #94a3b8; background: rgba(30, 41, 59, 0.6); border: 1px solid #334155; border-radius: 12px;">
            <p style="font-size: 16px;">No departments found</p>
        </div>
    @endforelse
</div>

<!-- Modal -->
<div id="deptModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add Department</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>

        <form id="deptForm" method="POST" action="{{ route('departments.store') }}">
            @csrf
            <input type="hidden" id="deptId" name="department_id">
            <input type="hidden" id="deptMethod" name="_method" value="POST">

            <div class="form-group">
                <label class="form-label">Department Name <span class="required">*</span></label>
                <input type="text" name="name" id="deptName" class="form-input" placeholder="Enter department name" required>
            </div>

            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" id="deptDesc" class="form-input" placeholder="Enter department description" style="resize: vertical; min-height: 80px;"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Manager <span class="required">*</span></label>
                <select name="manager_id" id="deptManager" class="form-select" required>
                    <option value="">Select a manager</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="required">*</span></label>
                <select name="status" id="deptStatus" class="form-select" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Department</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('modalTitle').textContent = 'Add Department';
        document.getElementById('deptForm').action = "{{ route('departments.store') }}";
        document.getElementById('deptMethod').value = 'POST';
        document.getElementById('deptId').value = '';
        document.getElementById('deptName').value = '';
        document.getElementById('deptDesc').value = '';
        document.getElementById('deptManager').value = '';
        document.getElementById('deptStatus').value = 'active';
        document.getElementById('deptModal').classList.add('show');
    }

    function editDepartment(deptId) {
        // Fetch department details via AJAX
        fetch(`/departments/${deptId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Edit Department';
                document.getElementById('deptForm').action = `/departments/${deptId}`;
                document.getElementById('deptMethod').value = 'PATCH';
                document.getElementById('deptId').value = deptId;
                document.getElementById('deptName').value = data.name;
                document.getElementById('deptDesc').value = data.description || '';
                document.getElementById('deptManager').value = data.manager_id || '';
                document.getElementById('deptStatus').value = data.status || 'active';
                document.getElementById('deptModal').classList.add('show');
            });
    }

    function closeModal() {
        document.getElementById('deptModal').classList.remove('show');
    }

    // Close modal when clicking outside
    document.getElementById('deptModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
</script>
@endsection
