<x-app-layout>
    <x-slot name="title">Submit Leave Request</x-slot>

    <div class="hris-form-shell">
        <div class="hris-form-card">
            <div class="hris-form-header">
                <p class="hris-eyebrow">Leave Management</p>
                <h1 class="hris-form-title">Submit Leave Request</h1>
                <p class="hris-form-text">Create a new leave request for approval.</p>
            </div>

            @if ($errors->any())
                <div class="hris-alert hris-alert-error mb-4">
                    <ul class="mb-0 ps-3 d-grid gap-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('leave-requests.store') }}" class="d-grid gap-4">
                @csrf

                <div class="hris-form-group">
                    <label class="hris-form-label">Leave Type</label>
                    <select name="leave_type" class="hris-form-select" required>
                        <option value="">Select Leave Type</option>
                        <option value="sick_leave">Sick Leave</option>
                        <option value="vacation">Vacation</option>
                        <option value="personal">Personal Leave</option>
                        <option value="maternity">Maternity Leave</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="hris-form-grid hris-form-grid-2">
                    <div class="hris-form-group">
                        <label class="hris-form-label">Start Date</label>
                        <input type="date" name="start_date" class="hris-form-input" required>
                    </div>
                    <div class="hris-form-group">
                        <label class="hris-form-label">End Date</label>
                        <input type="date" name="end_date" class="hris-form-input" required>
                    </div>
                </div>

                <div class="hris-form-group">
                    <label class="hris-form-label">Reason</label>
                    <textarea name="reason" class="hris-form-textarea" rows="5" required></textarea>
                </div>

                <div class="hris-form-actions-row pt-2">
                    <a href="{{ route('leave-requests.index') }}" class="hris-btn-secondary">Cancel</a>
                    <button type="submit" class="hris-btn-primary">Submit Request</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
