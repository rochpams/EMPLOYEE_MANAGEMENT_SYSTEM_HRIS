<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@isset($title){{ $title }} - @endisset HRIS Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #e2e8f0;
            min-height: 100vh;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border-right: 1px solid #334155;
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 50;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid #334155;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: bold;
            color: #fff;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 8px;
            overflow-y: auto;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            margin-bottom: 4px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sidebar-nav a:hover {
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
        }

        .sidebar-nav a.active {
            background: #3b82f6;
            color: #fff;
            font-weight: 500;
        }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid #334155;
        }

        .logout-btn {
            width: 100%;
            padding: 10px 16px;
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* Main Content */
        .main-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .topbar {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            padding: 16px 32px;
            border-bottom: 1px solid #334155;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar-left {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .topbar-title {
            font-size: 20px;
            font-weight: 600;
            color: #fff;
        }

        .topbar-subtitle {
            font-size: 12px;
            color: #94a3b8;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 16px;
        }

        .user-label {
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            color: #3b82f6;
        }

        .content {
            flex: 1;
            overflow-y: auto;
            padding: 32px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 32px;
            gap: 24px;
        }

        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 8px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #94a3b8;
        }

        .btn-add {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .filter-section {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            display: flex;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            position: relative;
            min-width: 250px;
        }

        .search-input {
            width: 100%;
            padding: 10px 16px 10px 40px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid #475569;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 14px;
        }

        .search-input::placeholder {
            color: #64748b;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(15, 23, 42, 0.9);
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #64748b;
        }

        .filter-group {
            display: flex;
            gap: 12px;
        }

        .filter-select {
            padding: 10px 16px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid #475569;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 14px;
            cursor: pointer;
            min-width: 140px;
        }

        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .table-container {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid #334155;
            border-radius: 12px;
            overflow: hidden;
        }

        .employees-table {
            width: 100%;
            border-collapse: collapse;
        }

        .employees-table thead {
            background: rgba(15, 23, 42, 0.8);
            border-bottom: 2px solid #334155;
        }

        .employees-table th {
            padding: 16px;
            text-align: left;
            font-weight: 600;
            color: #cbd5e1;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .employees-table tbody tr {
            border-bottom: 1px solid #334155;
            transition: background 0.3s ease;
        }

        .employees-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }

        .employees-table td {
            padding: 16px;
            font-size: 14px;
            color: #e2e8f0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .status-inactive {
            background: rgba(168, 85, 247, 0.1);
            color: #a855f7;
        }

        .status-terminated {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .action-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            transform: scale(1.1);
        }

        .action-btn.delete {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        .action-btn.delete:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .no-data {
            text-align: center;
            padding: 48px 16px;
            color: #94a3b8;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 32px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #334155;
        }

        .modal-title {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
        }

        .modal-close {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-close:hover {
            color: #e2e8f0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #cbd5e1;
            font-size: 14px;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 10px 14px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid #475569;
            border-radius: 8px;
            color: #e2e8f0;
            font-size: 14px;
        }

        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #3b82f6;
            background: rgba(15, 23, 42, 0.9);
        }

        .form-input::placeholder {
            color: #64748b;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #334155;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-secondary {
            background: rgba(100, 116, 139, 0.2);
            color: #cbd5e1;
        }

        .btn-secondary:hover {
            background: rgba(100, 116, 139, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        /* Scrollbar Styles */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(71, 85, 105, 0.5);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(71, 85, 105, 0.7);
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                📋 HRIS
                <span style="font-size: 12px; color: #64748b; margin-left: auto;">Employee Management</span>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'hr')
                    <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                        👥 Employees
                    </a>
                    <a href="{{ route('departments.index') }}" class="sidebar-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                        🏢 Departments
                    </a>
                    <a href="{{ route('reports.index') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        📊 Reports
                    </a>
                @endif
                <a href="{{ route('attendance.index') }}" class="sidebar-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    ⏰ Attendance
                </a>
                <a href="{{ route('leave-requests.index') }}" class="sidebar-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
                    📋 Leave Requests
                </a>
                <a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    👤 My Profile
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn">🚪 Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Container -->
        <div class="main-container">
            <!-- Top Bar -->
            <div class="topbar">
                <div class="topbar-left">
                    <div class="topbar-title">Welcome back, {{ auth()->user()->name ?? 'User' }}</div>
                    <div class="topbar-subtitle">Today is {{ now()->format('l, F j, Y') }}</div>
                </div>
                <div class="topbar-right">
                    <div class="user-info">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'A' }}&background=3b82f6&color=fff" alt="avatar" class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                        <div>
                            <div style="font-weight: 500; color: #f1f5f9;">{{ auth()->user()->name ?? 'User' }}</div>
                            <div class="user-label">{{ strtoupper(auth()->user()->role ?? 'USER') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                {{ $slot }}
            </div>
        </div>
    </div>

    <script>
        // Setup sidebar active link
        document.querySelectorAll('.sidebar-link').forEach(link => {
            if (link.getAttribute('href') === window.location.pathname) {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>
