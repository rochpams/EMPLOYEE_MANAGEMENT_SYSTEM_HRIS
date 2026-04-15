<x-app-layout>
    <x-slot name="title">Reports</x-slot>

    <div class="max-w-6xl">
        <h2 class="text-3xl font-bold mb-6">HR Reports</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
            <!-- Employee Report -->
            <a href="{{ route('reports.show', 'employees') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg cursor-pointer transition">
                <div class="text-4xl font-bold text-blue-600 mb-2">👥</div>
                <h3 class="text-xl font-bold mb-2">Employee List Report</h3>
                <p class="text-gray-600 text-sm">View complete employee directory with details</p>
            </a>

            <!-- Attendance Report -->
            <a href="{{ route('reports.show', 'attendance') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg cursor-pointer transition">
                <div class="text-4xl font-bold text-green-600 mb-2">✓</div>
                <h3 class="text-xl font-bold mb-2">Attendance Report</h3>
                <p class="text-gray-600 text-sm">Track attendance summaries by employee</p>
            </a>

            <!-- Leave Request Report -->
            <a href="{{ route('reports.show', 'leaves') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg cursor-pointer transition">
                <div class="text-4xl font-bold text-yellow-600 mb-2">📅</div>
                <h3 class="text-xl font-bold mb-2">Leave Request Report</h3>
                <p class="text-gray-600 text-sm">Summarize leave requests by status and type</p>
            </a>

            <!-- Department Report -->
            <a href="{{ route('reports.show', 'departments') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg cursor-pointer transition">
                <div class="text-4xl font-bold text-purple-600 mb-2">🏢</div>
                <h3 class="text-xl font-bold mb-2">Department Report</h3>
                <p class="text-gray-600 text-sm">Department overview with employee count</p>
            </a>

            <!-- Activity Report -->
            <a href="{{ route('reports.show', 'activity') }}" class="bg-white rounded-lg shadow p-6 hover:shadow-lg cursor-pointer transition">
                <div class="text-4xl font-bold text-red-600 mb-2">📊</div>
                <h3 class="text-xl font-bold mb-2">Activity Report</h3>
                <p class="text-gray-600 text-sm">Recent HR activities and transactions</p>
            </a>
        </div>

        <!-- Sample Data Preview -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4">Quick Statistics</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['total_employees'] ?? 0 }}</p>
                    <p class="text-gray-600">Total Employees</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-green-600">{{ $stats['total_departments'] ?? 0 }}</p>
                    <p class="text-gray-600">Departments</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pending_leaves'] ?? 0 }}</p>
                    <p class="text-gray-600">Pending Leaves</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-purple-600">{{ $stats['today_attendance'] ?? 0 }}</p>
                    <p class="text-gray-600">Present Today</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
