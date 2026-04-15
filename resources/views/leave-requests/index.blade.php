<x-app-layout>
    <x-slot name="title">Leave Requests</x-slot>

    <div class="max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Leave Requests</h2>
            @if(auth()->user()->role === 'employee')
                <a href="{{ route('leave-requests.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    + Submit Leave Request
                </a>
            @endif
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-2 mb-6">
            <a href="{{ route('leave-requests.index', ['status' => '']) }}" class="px-4 py-2 rounded {{ request('status') === '' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                All
            </a>
            <a href="{{ route('leave-requests.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded {{ request('status') === 'pending' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                Pending
            </a>
            <a href="{{ route('leave-requests.index', ['status' => 'approved']) }}" class="px-4 py-2 rounded {{ request('status') === 'approved' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                Approved
            </a>
            <a href="{{ route('leave-requests.index', ['status' => 'rejected']) }}" class="px-4 py-2 rounded {{ request('status') === 'rejected' ? 'bg-blue-600 text-white' : 'bg-gray-200' }}">
                Rejected
            </a>
        </div>

        @if($leaveRequests->count())
            <div class="space-y-4">
                @foreach($leaveRequests as $leave)
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</h3>
                                <p class="text-gray-600">{{ $leave->leave_type }} - {{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d, Y') }}</p>
                                <p class="text-gray-500 text-sm mt-2">Reason: {{ $leave->reason }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $leave->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                                       ($leave->status === 'approved' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr' || auth()->user()->role === 'manager')
                                    @if($leave->status === 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('leave-requests.approve', $leave) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('leave-requests.reject', $leave) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Reject</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                                @if(auth()->user()->role === 'employee' && $leave->status === 'pending')
                                    <form action="{{ route('leave-requests.destroy', $leave) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No leave requests found
            </div>
        @endif
    </div>
                    <div class="bg-white rounded-lg shadow p-5">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</h3>
                                <p class="text-gray-600">{{ $leave->leave_type }} - {{ $leave->start_date->format('M d') }} to {{ $leave->end_date->format('M d, Y') }}</p>
                                <p class="text-gray-500 text-sm mt-2">Reason: {{ $leave->reason }}</p>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold
                                    {{ $leave->status === 'pending' ? 'bg-yellow-200 text-yellow-800' : 
                                       ($leave->status === 'approved' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800') }}">
                                    {{ ucfirst($leave->status) }}
                                </span>
                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr' || auth()->user()->role === 'manager')
                                    @if($leave->status === 'pending')
                                        <div class="flex gap-2">
                                            <form action="{{ route('leave-requests.approve', $leave) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-800 text-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('leave-requests.reject', $leave) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">Reject</button>
                                            </form>
                                        </div>
                                    @endif
                                @endif
                                @if(auth()->user()->role === 'employee' && $leave->status === 'pending')
                                    <form action="{{ route('leave-requests.destroy', $leave) }}" method="POST" class="inline" onsubmit="return confirm('Cancel this request?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-600 hover:text-gray-800 text-sm">Cancel</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                No leave requests found
            </div>
        @endif
    </div>
</x-app-layout>
