<x-app-layout>
    <x-slot name="title">Leave Requests Report</x-slot>

    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Leave Requests Report</h2>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Reports</a>
        </div>

        <div class="mb-6 bg-white rounded-lg shadow p-4">
            <h3 class="font-bold mb-4">Leave Summary</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $summary['pending'] ?? 0 }}</p>
                    <p class="text-gray-600">Pending</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $summary['approved'] ?? 0 }}</p>
                    <p class="text-gray-600">Approved</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $summary['rejected'] ?? 0 }}</p>
                    <p class="text-gray-600">Rejected</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $summary['total'] ?? 0 }}</p>
                    <p class="text-gray-600">Total</p>
                </div>
            </div>
        </div>

        @if($leaves->count())
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full divide-y">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Leave Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Start Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">End Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Days</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($leaves as $leave)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</td>
                                <td class="px-6 py-4 text-sm">{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</td>
                                <td class="px-6 py-4 text-sm">{{ $leave->start_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $leave->end_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $leave->end_date->diffInDays($leave->start_date) + 1 }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs {{ $leave->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : ($leave->status === 'approved' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">
                                        {{ ucfirst($leave->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No leave records available
            </div>
        @endif
    </div>
</x-app-layout>
