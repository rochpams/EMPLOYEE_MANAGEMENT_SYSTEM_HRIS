<x-app-layout>
    <x-slot name="title">Add Employee</x-slot>

    <div class="max-w-3xl mx-auto bg-black shadow rounded p-6">
        <h2 class="text-2xl font-bold mb-6">Add New Employee</h2>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employees.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Department</label>
                    <select name="department_id" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Position</label>
                    <input type="text" name="position" value="{{ old('position') }}" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Hire Date</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Status</label>
                    <select name="employment_status" class="w-full border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200">
                        <option value="active" {{ old('employment_status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('employment_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('employees.index') }}" class="mr-3 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-black rounded hover:bg-blue-700">Save Employee</button>
            </div>
        </form>
    </div>
</x-app-layout>