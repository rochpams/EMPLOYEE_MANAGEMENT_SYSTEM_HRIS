<x-app-layout>
    <x-slot name="title">Activity Report</x-slot>

    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold">Activity Report</h2>
            <a href="{{ route('reports.index') }}" class="text-blue-600 hover:text-blue-800">← Back to Reports</a>
        </div>

        <div class="mb-6 bg-white rounded-lg shadow p-4">
            <h3 class="font-bold mb-4">Recent Activities</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['today_checkins'] ?? 0 }}</p>
                    <p class="text-gray-600">Today Check-ins</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_approvals'] ?? 0 }}</p>
                    <p class="text-gray-600">Pending Approvals</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['approved_today'] ?? 0 }}</p>
                    <p class="text-gray-600">Approved Today</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-purple-600">{{ $stats['new_employees'] ?? 0 }}</p>
                    <p class="text-gray-600">New Employees</p>
                </div>
            </div>
        </div>

        @if($activities->count())
            <div class="bg-white rounded-lg shadow p-4">
                <h3 class="font-bold mb-4">Activity Log</h3>
                <div class="space-y-4">
                    @foreach($activities as $activity)
                        <div class="flex items-start gap-4 pb-4 border-b">
                            <div class="flex-1">
                                <p class="font-semibold">{{ $activity['title'] }}</p>
                                <p class="text-gray-600 text-sm">{{ $activity['description'] }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $activity['time'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No activity records available
            </div>
        @endif
    </div>
</x-app-layout>
