<x-app-layout>
    <x-slot name="title">Employee List Report</x-slot>

    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Employee List Report</h2>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Reports</a>
        </div>

        @if($employees->count())
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full divide-y">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Employee ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($employees as $emp)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm">{{ $emp->id }}</td>
                                <td class="px-6 py-4 text-sm">{{ $emp->first_name }} {{ $emp->last_name }}</td>
                                <td class="px-6 py-4 text-sm">{{ $emp->email }}</td>
                                <td class="px-6 py-4 text-sm">{{ $emp->department->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm">{{ $emp->position }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs {{ $emp->employment_status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                        {{ ucfirst($emp->employment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No employee data available
            </div>
        @endif
    </div>
</x-app-layout>
