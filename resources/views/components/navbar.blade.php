@auth
<div class="clinic-shell">
    <aside class="clinic-sidebar" id="clinicSidebar">
        <div class="sidebar-user">
            <a href="{{ route('account.edit') }}" class="sidebar-user-link">
                <span class="avatar avatar-large">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                <span>
                    <strong>{{ auth()->user()->name }}</strong>
                    <small>{{ ucfirst(auth()->user()->role) }}</small>
                </span>
            </a>
            <button type="button" class="sidebar-close d-lg-none" data-sidebar-close>×</button>
        </div>


        <nav class="sidebar-nav">
            <a class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}">
                <span class="sidebar-icon">⌂</span> Overview
            </a>

            @if (auth()->user()->role === 'admin')
            <a class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}"
                href="{{ route('users.index') }}">
                <span class="sidebar-icon">♙</span> Accounts
            </a>
            <a class="sidebar-link {{ request()->routeIs('patients.*') ? 'active' : '' }}"
                href="{{ route('patients.index') }}">
                <span class="sidebar-icon">♧</span> Patients
            </a>
            <a class="sidebar-link {{ request()->routeIs('doctors.*') ? 'active' : '' }}"
                href="{{ route('doctors.index') }}">
                <span class="sidebar-icon">✚</span> Doctors
            </a>
            @endif

            @if (auth()->user()->role === 'doctor')
            <a class="sidebar-link {{ request()->routeIs('availability.*') ? 'active' : '' }}"
                href="{{ route('availability.index') }}">
                <span class="sidebar-icon">◷</span> Availability
            </a>
            <a class="sidebar-link {{ request()->routeIs('doctors.patients.*') ? 'active' : '' }}"
                href="{{ route('doctors.patients.index') }}">
                <span class="sidebar-icon">♧</span> My patients
            </a>
            @endif

            <a class="sidebar-link {{ request()->routeIs('appointments.*') ? 'active' : '' }}"
                href="{{ route('appointments.index') }}">
                <span class="sidebar-icon">▣</span> Appointments
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a class="sidebar-link {{ request()->routeIs('account.*') ? 'active' : '' }}"
                href="{{ route('account.edit') }}">
                <span class="sidebar-icon">⚙</span> My account
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="sidebar-link sidebar-logout" type="submit">
                    <span class="sidebar-icon">↪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="clinic-content">
        <div class="clinic-page">
            <button type="button" class="menu-button mobile-menu-button d-lg-none" data-sidebar-open>☰</button>
            {{ $slot }}
        </div>
    </div>

    <div class="sidebar-backdrop d-lg-none" data-sidebar-close></div>
</div>
@endauth