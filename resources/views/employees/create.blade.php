<x-app-layout>
    <x-slot name="title">Add Employee</x-slot>

    <div class="hris-form-shell">
        <div class="hris-form-card">
            <div class="hris-form-header">
                <p class="hris-eyebrow">Employee Management</p>
                <h1 class="hris-form-title">Add New Employee</h1>
                <p class="hris-form-text">Create a new employee profile and assign them to a department.</p>
            </div>

            @if ($errors->any())
                <div class="hris-alert hris-alert-error mb-4">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('employees.store') }}" class="space-y-5">
                @csrf

                <div class="hris-form-grid hris-form-grid-2">
                    <div class="hris-form-group">
                        <label class="hris-form-label">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="hris-form-input">
                    </div>
                    <div class="hris-form-group">
                        <label class="hris-form-label">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="hris-form-input">
                    </div>
                </div>

                <div class="hris-form-group">
                    <label class="hris-form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="hris-form-input">
                </div>

                <div class="hris-form-grid hris-form-grid-2">
                    <div class="hris-form-group">
                        <label class="hris-form-label">Department</label>
                        <select name="department_id" class="hris-form-select">
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="hris-form-group">
                        <label class="hris-form-label">Position</label>
                        <input type="text" name="position" value="{{ old('position') }}" class="hris-form-input">
                    </div>
                </div>

                <div class="hris-form-grid hris-form-grid-2">
                    <div class="hris-form-group">
                        <label class="hris-form-label">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="hris-form-input">
                    </div>
                    <div class="hris-form-group">
                        <label class="hris-form-label">Hire Date</label>
                        <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="hris-form-input">
                    </div>
                </div>

                <div class="hris-form-group">
                    <label class="hris-form-label">Address</label>
                    <textarea name="address" class="hris-form-textarea">{{ old('address') }}</textarea>
                </div>

                <div class="hris-form-group">
                    <label class="hris-form-label">Employment Status</label>
                    <select name="employment_status" class="hris-form-select">
                        <option value="active" {{ old('employment_status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('employment_status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="terminated" {{ old('employment_status') === 'terminated' ? 'selected' : '' }}>Terminated</option>
                    </select>
                </div>

                <div class="hris-form-actions-row pt-2">
                    <a href="{{ route('employees.index') }}" class="hris-btn-secondary">Cancel</a>
                    <button type="submit" class="hris-btn-primary">Save Employee</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>