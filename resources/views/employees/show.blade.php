<x-app-layout>
    <x-slot name="title">Employee Details - {{ $employee->first_name }} {{ $employee->last_name }}</x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Employee Details</h2>
            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
                <a href="{{ route('employees.edit', $employee) }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Edit
                </a>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600 text-sm">First Name</p>
                    <p class="text-lg font-semibold">{{ $employee->first_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Last Name</p>
                    <p class="text-lg font-semibold">{{ $employee->last_name }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Email</p>
                    <p class="text-lg font-semibold">{{ $employee->email }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Phone</p>
                    <p class="text-lg font-semibold">{{ $employee->phone ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Department</p>
                    <p class="text-lg font-semibold">{{ $employee->department->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Position</p>
                    <p class="text-lg font-semibold">{{ $employee->position }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Hire Date</p>
                    <p class="text-lg font-semibold">{{ $employee->hire_date->format('M d, Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Employment Status</p>
                    <p class="text-lg font-semibold">
                        <span class="px-3 py-1 rounded-full text-sm {{ $employee->employment_status === 'active' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                            {{ ucfirst($employee->employment_status) }}
                        </span>
                    </p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-600 text-sm">Address</p>
                    <p class="text-lg font-semibold">{{ $employee->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('employees.index') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Employees
            </a>
        </div>
    </div>
</x-app-layout>
