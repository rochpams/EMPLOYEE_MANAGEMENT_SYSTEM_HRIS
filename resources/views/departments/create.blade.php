<x-app-layout>
    <x-slot name="title">Add Department</x-slot>

    <div class="hris-form-shell">
        <div class="hris-form-card">
            <div class="hris-form-header">
                <p class="hris-eyebrow">Department Management</p>
                <h1 class="hris-form-title">Add Department</h1>
                <p class="hris-form-text">Create a department and assign a manager.</p>
            </div>

            @if ($errors->any())
                <div class="hris-alert hris-alert-error mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('departments.store') }}" class="d-grid gap-3">
                @csrf

                <div>
                    <label class="form-label">Department Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="form-label">Manager</label>
                    <select name="manager_id" class="form-select">
                        <option value="">Select Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-2">
                    <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Department</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
