<x-app-layout>
    <x-slot name="title">Attendance Report</x-slot>

    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Attendance Report</h2>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Reports</a>
        </div>

        <div class="mb-6 bg-white rounded-lg shadow p-4">
            <h3 class="font-bold mb-4">Attendance Summary</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $summary['present'] ?? 0 }}</p>
                    <p class="text-gray-600">Present</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $summary['absent'] ?? 0 }}</p>
                    <p class="text-gray-600">Absent</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $summary['late'] ?? 0 }}</p>
                    <p class="text-gray-600">Late</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $summary['on_leave'] ?? 0 }}</p>
                    <p class="text-gray-600">On Leave</p>
                </div>
            </div>
        </div>

        @if($attendances->count())
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full divide-y">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Time In</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Time Out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($attendances as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $record->employee->first_name }} {{ $record->employee->last_name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">{{ $record->time_in?->format('H:i:s') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $record->time_out?->format('H:i:s') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs {{ $record->status === 'present' ? 'bg-green-200 text-green-800' : ($record->status === 'absent' ? 'bg-red-200 text-red-800' : 'bg-yellow-200 text-yellow-800') }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No attendance records available
            </div>
        @endif
    </div>
</x-app-layout>
