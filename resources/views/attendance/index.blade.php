<x-app-layout>
    <x-slot name="title">Attendance</x-slot>

    <div class="max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2">Attendance</h1>
            <p class="text-gray-400">Track and manage employee attendance</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-4 gap-6 mb-8">
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-gray-400 text-sm mb-2">Total Employees</p>
                <p class="text-4xl font-bold text-white">{{ $totalEmployees ?? count($employees) }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-gray-400 text-sm mb-2">Present Today</p>
                <p class="text-4xl font-bold text-green-400">{{ $presentToday ?? 0 }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-gray-400 text-sm mb-2">Late Arrivals</p>
                <p class="text-4xl font-bold text-yellow-400">{{ $lateArrivals ?? 0 }}</p>
            </div>
            <div class="bg-gray-800 rounded-lg p-6 border border-gray-700">
                <p class="text-gray-400 text-sm mb-2">Absent</p>
                <p class="text-4xl font-bold text-red-400">{{ $absent ?? 0 }}</p>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-800 rounded-lg p-6 border border-gray-700 mb-8">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-white">Filter Attendance</h3>
            </div>
            <form action="{{ route('attendance.index') }}" method="GET" class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Date</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Employee</label>
                    <select name="employee_id" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                        <option value="">All Employees</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-300 text-sm font-medium mb-2">Status</label>
                    <select name="status" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                        <option value="">All Statuses</option>
                        <option value="present" {{ request('status') === 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') === 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="late" {{ request('status') === 'late' ? 'selected' : '' }}>Late</option>
                    </select>
                </div>
                <div class="col-span-3 flex gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        Filter
                    </button>
                    <a href="{{ route('attendance.index') }}" class="bg-gray-700 text-white px-6 py-2 rounded hover:bg-gray-600 transition">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Time In/Out Form -->
        @if(auth()->user()->role === 'employee' || auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
            <div class="bg-gray-800 rounded-lg border border-gray-700 p-6 mb-8">
                <h3 class="text-lg font-semibold text-white mb-4">Record Attendance</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-gray-300 text-sm font-medium mb-2">Employee</label>
                        <select id="employeeSelect" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500">
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <form action="{{ route('attendance.time-in') }}" method="POST">
                            @csrf
                            <input type="hidden" name="employee_id" id="empIdIn">
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition font-medium">
                                Time In
                            </button>
                        </form>
                        <form action="{{ route('attendance.time-out') }}" method="POST">
                            @csrf
                            <input type="hidden" name="employee_id" id="empIdOut">
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition font-medium">
                                Time Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Attendance Records Table -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 overflow-hidden">
            <table class="w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Employee</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Department</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Time In</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Time Out</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-300">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @if($attendances->count())
                        @foreach($attendances as $record)
                            <tr class="hover:bg-gray-750 transition">
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $record->attendance_date->format('m/d/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-200">{{ $record->employee->first_name }} {{ $record->employee->last_name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $record->employee->department?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $record->time_in?->format('H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-300">{{ $record->time_out?->format('H:i') ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($record->status === 'present')
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-900/30 text-green-400">Present</span>
                                    @elseif($record->status === 'absent')
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-900/30 text-red-400">Absent</span>
                                    @elseif($record->status === 'late')
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-900/30 text-yellow-400">Late</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-gray-900/30 text-gray-400">{{ ucfirst($record->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">-</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    No attendance records found
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            @if($attendances->count())
                <div class="bg-gray-900 px-6 py-4 text-sm text-gray-400">
                    Showing {{ $attendances->count() }} attendance {{ $attendances->count() == 1 ? 'record' : 'records' }}
                </div>
            @else
                <div class="bg-gray-900 px-6 py-4 text-sm text-gray-400">
                    Showing 0 attendance records
                </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('employeeSelect').addEventListener('change', function() {
            document.getElementById('empIdIn').value = this.value;
            document.getElementById('empIdOut').value = this.value;
        });
    </script>
</x-app-layout>
