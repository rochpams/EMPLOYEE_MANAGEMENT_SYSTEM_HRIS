<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HRIS Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a2a4e 0%, #0f1929 100%);
            color: #e0e7ff;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #1a2a4e 0%, #0f1929 100%);
            border-right: 1px solid rgba(59, 130, 246, 0.2);
            position: relative;
        }
        
        .nav-item {
            color: #a5b4fc;
            transition: all 0.3s ease;
        }
        
        .nav-item:hover, .nav-item.active {
            color: #fff;
            background: rgba(59, 130, 246, 0.2);
        }
        
        .stat-card {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .icon-blue { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .icon-purple { background: rgba(168, 85, 247, 0.2); color: #a855f7; }
        .icon-green { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
        .icon-orange { background: rgba(249, 115, 22, 0.2); color: #f97316; }
        
        .activity-item {
            border-left: 2px solid #3b82f6;
            padding-left: 16px;
        }
        
        .badge-green { background: rgba(34, 197, 94, 0.2); color: #22c55e; }
        .badge-blue { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        
        .header-top {
            background: rgba(30, 41, 59, 0.8);
            border-bottom: 1px solid rgba(59, 130, 246, 0.2);
        }
    </style>
</head>
<body class="min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 min-h-screen p-6">
            <div class="flex items-center gap-3 mb-12">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                    
                </div>
                <div>
                    <h1 class="text-white font-bold text-lg">HRIS</h1>
                    <p class="text-xs text-blue-400">Employee Management</p>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="nav-item active px-4 py-3 rounded-lg cursor-pointer inline-block w-full">
                    <span class="text-lg"></span> Dashboard
                </a>
                
                @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                    <a href="{{ route('employees.index') }}" class="nav-item px-4 py-3 rounded-lg cursor-pointer inline-block w-full">
                        <span class="text-lg"></span> Employees
                    </a>
                    <a href="{{ route('departments.index') }}" class="nav-item px-4 py-3 rounded-lg cursor-pointer inline-block w-full">
                        <span class="text-lg"></span> Departments
                    </a>
                    <a href="{{ route('reports.index') }}" class="nav-item px-4 py-3 rounded-lg cursor-pointer inline-block w-full">
                        <span class="text-lg"></span> Reports
                    </a>
                @endif
                
                <a href="{{ route('attendance.index') }}" class="nav-item px-4 py-3 rounded-lg cursor-pointer inline-block w-full">
                    <span class="text-lg"></span> My Attendance
                </a>
                <a href="{{ route('leave-requests.index') }}" class="nav-item px-4 py-3 rounded-lg cursor-pointer inline-block w-full">
                    <span class="text-lg"></span> My Leave Requests
                </a>
            </nav>

            <div class="absolute bottom-6 left-6 right-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-item w-full px-4 py-3 rounded-lg text-center hover:bg-red-500/10 hover:text-red-400">
                        <span class="text-lg"></span> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1">
            <!-- Header -->
            <div class="header-top px-8 py-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-white">Welcome back, {{ Auth::user()->name }}</h2>
                    <p class="text-gray-400 text-sm">Today is {{ now()->format('l, F j, Y') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-white cursor-pointer"></div>
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">{{ Auth::user()->name }}</p>
                        <p class="text-gray-400 text-xs">{{ strtoupper(Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Dashboard Title -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-white mb-2">Dashboard</h1>
                    @if(Auth::user()->isAdmin())
                        <p class="text-gray-400">Admin Overview</p>
                    @elseif(Auth::user()->isHR())
                        <p class="text-gray-400">Overview of your HR management system</p>
                    @elseif(Auth::user()->isManager())
                        <p class="text-gray-400">Your team overview</p>
                    @else
                        <p class="text-gray-400">Welcome back! Here's your overview</p>
                    @endif
                </div>

                <!-- Stats Grid - Role Based -->
                <div class="grid grid-cols-4 gap-6 mb-8">
                    @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                        <!-- Total Employees -->
                        <div class="stat-card p-6 hover:border-blue-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">Total Employees</p>
                                    <p class="text-4xl font-bold text-white">{{ $totalEmployees ?? 0 }}</p>
                                    <p class="text-gray-500 text-xs mt-2">{{ $totalEmployees ?? 0 }} active</p>
                                </div>
                                <div class="stat-icon icon-blue"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4">📈 +12% from last month</p>
                        </div>

                        <!-- Departments -->
                        <div class="stat-card p-6 hover:border-purple-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">Departments</p>
                                    <p class="text-4xl font-bold text-white">{{ $totalDepartments ?? 0 }}</p>
                                    <p class="text-gray-500 text-xs mt-2">Active departments</p>
                                </div>
                                <div class="stat-icon icon-purple"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4"> +2 from last month</p>
                        </div>

                        <!-- Present Today -->
                        <div class="stat-card p-6 hover:border-green-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">Present Today</p>
                                    <p class="text-4xl font-bold text-white">{{ $presentToday ?? 0 }}</p>
                                    <p class="text-gray-500 text-xs mt-2">Out of {{ $totalEmployees ?? 0 }} employees</p>
                                </div>
                                <div class="stat-icon icon-green"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4"> 94% from last month</p>
                        </div>

                        <!-- Pending Leaves -->
                        <div class="stat-card p-6 hover:border-orange-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">Pending Leaves</p>
                                    <p class="text-4xl font-bold text-white">{{ $pendingLeaveRequests ?? 0 }}</p>
                                    <p class="text-gray-500 text-xs mt-2">Awaiting approval</p>
                                </div>
                                <div class="stat-icon icon-orange"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4"> {{ $pendingLeaveRequests ?? 0 }} new from last month</p>
                        </div>
                    @else
                        <!-- Employee Dashboard stats -->
                        <!-- My Attendance -->
                        <div class="stat-card p-6 hover:border-blue-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">My Attendance</p>
                                    <p class="text-4xl font-bold text-white">1</p>
                                    <p class="text-gray-500 text-xs mt-2">Total records</p>
                                </div>
                                <div class="stat-icon icon-blue"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4"> 1 today</p>
                        </div>

                        <!-- My Leave Requests -->
                        <div class="stat-card p-6 hover:border-purple-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">My Leave Requests</p>
                                    <p class="text-4xl font-bold text-white">1</p>
                                    <p class="text-gray-500 text-xs mt-2">0 approved</p>
                                </div>
                                <div class="stat-icon icon-purple"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4"> 1 pending</p>
                        </div>

                        <!-- This Month -->
                        <div class="stat-card p-6 hover:border-green-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">This Month</p>
                                    <p class="text-4xl font-bold text-white">1</p>
                                    <p class="text-gray-500 text-xs mt-2">Days worked</p>
                                </div>
                                <div class="stat-icon icon-green"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4"> Active</p>
                        </div>

                        <!-- Leave Balance -->
                        <div class="stat-card p-6 hover:border-orange-400 transition">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-400 text-sm mb-2">Leave Balance</p>
                                    <p class="text-4xl font-bold text-white">15</p>
                                    <p class="text-gray-500 text-xs mt-2">Days remaining</p>
                                </div>
                                <div class="stat-icon icon-orange"></div>
                            </div>
                            <p class="text-green-400 text-sm mt-4"> Annual</p>
                        </div>
                    @endif
                </div>

                <!-- Bottom Section -->
                <div class="grid grid-cols-2 gap-6">
                    @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                        <!-- Recent Activity -->
                        <div class="stat-card p-6">
                            <h3 class="text-lg font-bold text-white mb-2">Recent Activity</h3>
                            <p class="text-gray-400 text-sm mb-6">Latest HR system updates</p>
                            
                            <div class="space-y-4">
                                @if(isset($recentActivity) && count($recentActivity) > 0)
                                    @foreach($recentActivity as $activity)
                                        <div class="activity-item">
                                            <div class="flex justify-between">
                                                <p class="text-white font-semibold">{{ $activity['title'] }}</p>
                                                <p class="text-gray-500 text-sm">{{ $activity['time'] }}</p>
                                            </div>
                                            <p class="text-gray-400 text-sm">{{ $activity['description'] }}</p>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-500 text-center py-8">No recent activity</p>
                                @endif
                            </div>
                        </div>

                        <!-- Upcoming Approved Leaves -->
                        <div class="stat-card p-6">
                            <h3 class="text-lg font-bold text-white mb-2">Upcoming Approved Leaves</h3>
                            <p class="text-gray-400 text-sm mb-6">Scheduled employee absences</p>
                            
                            <div class="space-y-4">
                                @if(isset($upcomingLeaves) && count($upcomingLeaves) > 0)
                                    @foreach($upcomingLeaves as $leave)
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-white font-semibold">{{ $leave['employee_name'] }}</p>
                                                <p class="text-blue-400 text-sm">{{ $leave['leave_type'] }}</p>
                                                <p class="text-gray-500 text-xs mt-1">{{ $leave['date_range'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-500 text-center py-8">No upcoming approved leaves</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- My Recent Activity -->
                        <div class="stat-card p-6">
                            <h3 class="text-lg font-bold text-white mb-2">My Recent Activity</h3>
                            <p class="text-gray-400 text-sm mb-6">Your latest activities</p>
                            
                            <div class="space-y-4">
                                <div class="activity-item">
                                    <div class="flex justify-between">
                                        <p class="text-white font-semibold">Attendance marked</p>
                                        <p class="text-gray-500 text-sm">Today, 8:30 AM</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">You</p>
                                </div>

                                <div class="activity-item">
                                    <div class="flex justify-between">
                                        <p class="text-white font-semibold">Leave request submitted</p>
                                        <p class="text-gray-500 text-sm">2 days ago</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">You</p>
                                </div>
                            </div>
                        </div>

                        <!-- My Upcoming Leaves -->
                        <div class="stat-card p-6">
                            <h3 class="text-lg font-bold text-white mb-2">My Upcoming Leaves</h3>
                            <p class="text-gray-400 text-sm mb-6">Your scheduled absences</p>
                            
                            <div class="flex items-center justify-center h-40">
                                <p class="text-gray-500">No upcoming leaves scheduled</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>