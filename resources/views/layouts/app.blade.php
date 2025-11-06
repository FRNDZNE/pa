<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <title>@yield('title')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('/') }}assets/images/favicon.ico" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="{{ asset('/') }}assets/style/main.css" rel="stylesheet" />
    @livewireStyles
</head>

<body>
    <div class="page-dashboard">
        <div class="d-flex" id="wrapper" data-aos="fade-right">
            <!-- sidebar -->
            <div class="border-right" id="sidebar-wrapper">
                <div class="sidebar-heading text-center">
                    <img src="{{ asset('/') }}assets/images/logo-assesmen.svg" alt="logo" class="my-4" />
                </div>
                <div class="list-group list-group-flush">
                    @if (Auth::user()->role->name == 'admin')
                        @include('layouts.sidebar.admin')
                    @elseif(Auth::user()->role->name == 'lecturer')
                        @include('layouts.sidebar.lecturer')
                    @elseif(Auth::user()->role->name == 'student')
                        @include('layouts.sidebar.student')
                    @endif
                </div>
            </div>

            <!-- Page Content -->
            <div id="page-content-wrapper">
                <nav class="navbar navbar-expand-lg navbar-light navbar-store fixed-top" data-aos="fade-down">
                    <div class="container-fluid">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Desktop Menu -->
                            <ul class="navbar-nav d-none d-lg-flex ml-auto">
                                <li class="nav-item dropdown">
                                    <a href="#" class="nav-link" id="navbarDropdown" role="button"
                                        data-toggle="dropdown">
                                        {{-- Image User Soon --}}
                                        <img src="{{ asset('/') }}assets/images/user.png" alt="profile-icon"
                                            class="rounded-circle mr-2 profile-picture" />
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu">
                                        <a href="/dashboard.html" class="dropdown-item">Dashboard</a>
                                        <a href="/dashboard-account.html" class="dropdown-item">Account</a>
                                        <div class="dropdown-divider"></div>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                            class="dropdown-item">Logout</a>
                                        <form action="{{ route('logout') }}" method="post" id="logout-form"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link d-inline-block">
                                        <img src="{{ asset('/') }}assets/images/white-bell.svg" alt="icon-cart" />
                                        <div class="card-badge">3</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <!-- Section Content -->
                <div class="section-content section-dsahboard-home" data-aos="fade-up">
                    <div class="container-fluid">
                        <div class="dashboard-heading">
                            <h2 class="dashboard-title">@yield('title')</h2>
                            <p class="dashboard-subtitle">@yield('page-subtitle')</p>
                        </div>
                        <div class="dashboard-content">
                            @yield('content')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('/') }}assets/vendor/jquery/jquery.slim.min.js"></script>
    <script src="{{ asset('/') }}assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
    <script src="{{ asset('/') }}assets/script/navbar-scroll.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @livewireScripts
</body>

</html>
