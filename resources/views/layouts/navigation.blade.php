<nav class="navbar navbar-expand-lg bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <x-application-logo class="d-block" />
            <span class="fw-semibold">HRIS</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primaryNav" aria-controls="primaryNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="primaryNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 gap-lg-2">
                <li class="nav-item">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </li>

                <li class="nav-item">
                    <x-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.*')">
                        {{ __('Attendance') }}
                    </x-nav-link>
                </li>

                @if(Auth::user()->isAdmin() || Auth::user()->isHR())
                    <li class="nav-item">
                        <x-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                            {{ __('Employees') }}
                        </x-nav-link>
                    </li>

                    <li class="nav-item">
                        <x-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                            {{ __('Reports') }}
                        </x-nav-link>
                    </li>
                @endif
            </ul>

            <div class="d-flex align-items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="btn btn-light border d-inline-flex align-items-center gap-2" type="button">
                            <span>{{ Auth::user()->name }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
