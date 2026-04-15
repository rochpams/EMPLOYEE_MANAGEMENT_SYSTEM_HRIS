<x-app-layout>
    <x-slot name="title">Submit Leave Request</x-slot>

    <div class="max-w-2xl mx-auto">
        <h2 class="text-3xl font-bold mb-6">Submit Leave Request</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">
            <form method="POST" action="{{ route('leave-requests.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block font-semibold mb-1">Leave Type</label>
                    <select name="leave_type" class="w-full border border-gray-300 rounded px-3 py-2" required>
                        <option value="">Select Leave Type</option>
                        <option value="sick_leave">Sick Leave</option>
                        <option value="vacation">Vacation</option>
                        <option value="personal">Personal Leave</option>
                        <option value="maternity">Maternity Leave</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-1">Start Date</label>
                        <input type="date" name="start_date" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">End Date</label>
                        <input type="date" name="end_date" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    </div>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Reason</label>
                    <textarea name="reason" class="w-full border border-gray-300 rounded px-3 py-2" rows="5" required></textarea>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Submit Request
                    </button>
                    <a href="{{ route('leave-requests.index') }}" class="flex-1 bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
