<div class="p-3 border-bottom">
    <a href="{{ route('dashboard') }}" class="app-brand" aria-label="Go to dashboard">
        <span class="app-brand-icon"><i class="bi bi-building"></i></span>
        <span>
            <span class="d-block fw-bold text-dark">HRIS</span>
            <span class="d-block small text-muted">Employee Management</span>
        </span>
    </a>
</div>

<nav class="p-3 d-grid gap-1" aria-label="Main navigation">
    <a href="{{ route('dashboard') }}" class="app-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2" aria-hidden="true"></i>
        <span>Dashboard</span>
    </a>

    @if(auth()->user()->isManager())
        <div class="px-2 pt-2 pb-1 small text-uppercase text-muted fw-semibold">Manager</div>
        <a href="{{ route('manager.department-employees.index') }}" class="app-nav-link {{ request()->routeIs('manager.department-employees.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill" aria-hidden="true"></i>
            <span>Department Employees</span>
        </a>
    @endif

    @if(auth()->user()->isAdmin() || auth()->user()->isHR())
        <a href="{{ route('employees.index') }}" class="app-nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}">
            <i class="bi bi-people" aria-hidden="true"></i>
            <span>Employees</span>
        </a>

        <a href="{{ route('departments.index') }}" class="app-nav-link {{ request()->routeIs('departments.*') ? 'active' : '' }}">
            <i class="bi bi-diagram-3" aria-hidden="true"></i>
            <span>Departments</span>
        </a>

        <a href="{{ route('reports.index') }}" class="app-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart" aria-hidden="true"></i>
            <span>Reports</span>
        </a>
    @endif

    <a href="{{ route('attendance.index') }}" class="app-nav-link {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
        <i class="bi bi-clock-history" aria-hidden="true"></i>
        <span>Attendance</span>
    </a>

    <a href="{{ route('leave-requests.index') }}" class="app-nav-link {{ request()->routeIs('leave-requests.*') ? 'active' : '' }}">
        <i class="bi bi-calendar2-check" aria-hidden="true"></i>
        <span>Leave Requests</span>
    </a>

    <a href="{{ route('profile.edit') }}" class="app-nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
        <i class="bi bi-person-gear" aria-hidden="true"></i>
        <span>Profile</span>
    </a>
</nav>

<div class="mt-auto p-3 border-top">
    <div class="small text-muted mb-2">Signed in as</div>
    <div class="fw-semibold text-dark mb-3 text-truncate" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</div>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100" aria-label="Log out">
            <i class="bi bi-box-arrow-right me-2" aria-hidden="true"></i>
            Log Out
        </button>
    </form>
</div>
