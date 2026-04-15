<x-app-layout>
    <x-slot name="title">Department Report</x-slot>

    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Department Report</h2>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Reports</a>
        </div>

        @if($departments->count())
            <div class="space-y-6">
                @foreach($departments as $dept)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-2xl font-bold">{{ $dept->name }}</h3>
                                <p class="text-gray-600">{{ $dept->description ?? 'No description' }}</p>
                                <p class="text-sm text-gray-500 mt-2">Manager: {{ $dept->manager->name ?? 'N/A' }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm {{ $dept->status === 'active' ? 'bg-green-200 text-green-800' : 'bg-gray-200' }}">
                                {{ ucfirst($dept->status) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <p class="font-semibold text-lg text-blue-600">{{ $dept->employees->count() }} Employees</p>
                        </div>

                        @if($dept->employees->count())
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-4 py-2 text-left">Employee</th>
                                        <th class="px-4 py-2 text-left">Position</th>
                                        <th class="px-4 py-2 text-left">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dept->employees as $emp)
                                        <tr class="border-b">
                                            <td class="px-4 py-2">{{ $emp->first_name }} {{ $emp->last_name }}</td>
                                            <td class="px-4 py-2">{{ $emp->position }}</td>
                                            <td class="px-4 py-2">
                                                <span class="px-2 py-1 rounded text-xs {{ $emp->employment_status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                                    {{ ucfirst($emp->employment_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No department data available
            </div>
        @endif
    </div>
</x-app-layout>
