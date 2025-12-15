<aside class="app-sidebar bg-body-tertiary shadow" data-bs-theme="light">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
        <!--begin::Brand Link-->
        <a href="{{ route('home') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img src="{{ asset('/') }}/assets/images/logo-assesmen.svg" alt="AdminLTE Logo"
                class="brand-image opacity-75 shadow" />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">POA</span>
            <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">
                <li class="nav-header">Dashboard</li>
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ Route::is('*.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @if (Auth::user()->role->name == 'admin')
                    @include('layouts.partials.sidebar.admin')
                @elseif(Auth::user()->role->name == 'lecturer')
                    @include('layouts.partials.sidebar.lecturer')
                @elseif(Auth::user()->role->name == 'student')
                    @include('layouts.partials.sidebar.student')
                @endif
            </ul>
            <!--end::Sidebar Menu-->
        </nav>
    </div>
    <!--end::Sidebar Wrapper-->
</aside>
