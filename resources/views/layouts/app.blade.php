<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('/') }}/assets/images/favicon.ico">
    <!-- App css -->
    <!-- third party css -->
    <link href="{{ asset('/') }}/assets/libs/toastr/build/toastr.min.css" rel="stylesheet" type="text/css" />

    <link href="{{ asset('/') }}/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('/') }}/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}/assets/libs/datatables.net-select-bs5/css//select.bootstrap5.min.css"
        rel="stylesheet" type="text/css" />
    <!-- third party css end -->
    <link href="{{ asset('/') }}/assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style" />
    <!-- icons -->
    <link href="{{ asset('/') }}/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    @livewireStyles
</head>
<!-- body start -->

<body class="loading" data-layout-color="light" data-layout-mode="default" data-layout-size="fluid"
    data-topbar-color="light" data-leftbar-position="fixed" data-leftbar-color="light" data-leftbar-size='default'
    data-sidebar-user='true'>
    <!-- Begin page -->
    <div id="wrapper">
        <!-- Topbar Start -->
        <div class="navbar-custom">
            <ul class="list-unstyled topnav-menu float-end mb-0">
                <li class="dropdown d-inline-block d-lg-none">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect waves-light" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fe-search noti-icon"></i>
                    </a>
                    <div class="dropdown-menu dropdown-lg dropdown-menu-end p-0">
                        <form class="p-3">
                            <input type="text" class="form-control" placeholder="Search ..."
                                aria-label="Recipient's username">
                        </form>
                    </div>
                </li>
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light" data-bs-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="fe-bell noti-icon"></i>
                        <span class="badge bg-danger rounded-circle noti-icon-badge">9</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end">
                                    <a href="" class="text-dark">
                                        <small>Clear All</small>
                                    </a>
                                </span>Notification
                            </h5>
                        </div>
                        <div class="noti-scroll" data-simplebar>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item active">
                                <div class="notify-icon">
                                    <img src="{{ asset('/') }}/assets/images/users/user-1.jpg"
                                        class="img-fluid rounded-circle" alt="" />
                                </div>
                                <p class="notify-details">Cristina Pride</p>
                                <p class="text-muted mb-0 user-msg">
                                    <small>Hi, How are you? What about our next meeting</small>
                                </p>
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-primary">
                                    <i class="mdi mdi-comment-account-outline"></i>
                                </div>
                                <p class="notify-details">Caleb Flakelar commented on Admin
                                    <small class="text-muted">1 min ago</small>
                                </p>
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon">
                                    <img src="{{ asset('/') }}/assets/images/users/user-4.jpg"
                                        class="img-fluid rounded-circle" alt="" />
                                </div>
                                <p class="notify-details">Karen Robinson</p>
                                <p class="text-muted mb-0 user-msg">
                                    <small>Wow ! this admin looks good and awesome design</small>
                                </p>
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-warning">
                                    <i class="mdi mdi-account-plus"></i>
                                </div>
                                <p class="notify-details">New user registered.
                                    <small class="text-muted">5 hours ago</small>
                                </p>
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-info">
                                    <i class="mdi mdi-comment-account-outline"></i>
                                </div>
                                <p class="notify-details">Caleb Flakelar commented on Admin
                                    <small class="text-muted">4 days ago</small>
                                </p>
                            </a>
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item">
                                <div class="notify-icon bg-secondary">
                                    <i class="mdi mdi-heart"></i>
                                </div>
                                <p class="notify-details">Carlos Crouch liked
                                    <b>Admin</b>
                                    <small class="text-muted">13 days ago</small>
                                </p>
                            </a>
                        </div>
                        <!-- All-->
                        <a href="javascript:void(0);"
                            class="dropdown-item text-center text-primary notify-item notify-all">
                            View all
                            <i class="fe-arrow-right"></i>
                        </a>

                    </div>
                </li>
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                        data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                        aria-expanded="false">
                        <img src="{{ asset('/') }}/assets/images/users/user-1.jpg" alt="user-image"
                            class="rounded-circle">
                        <span class="pro-user-name ms-1">
                            {{ Auth::user()->name }} <i class="mdi mdi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a href="contacts-profile.html" class="dropdown-item notify-item">
                            <i class="fe-user"></i>
                            <span>My Account</span>
                        </a>
                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <a href="{{ route('logout') }}"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                            class="dropdown-item notify-item">
                            <i class="fe-log-out"></i>
                            <span>Logout</span>
                        </a>
                        <form action="{{ route('logout') }}" method="post" id="logout-form" class="d-none">
                            @csrf
                        </form>

                    </div>
                </li>
            </ul>

            <!-- LOGO -->
            <div class="logo-box">
                <a href="index.html" class="logo logo-light text-center">
                    <span class="logo-sm">
                        <img src="{{ asset('/') }}/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('/') }}/assets/images/logo-light.png" alt="" height="16">
                    </span>
                </a>
                <a href="index.html" class="logo logo-dark text-center">
                    <span class="logo-sm">
                        <img src="{{ asset('/') }}/assets/images/logo-sm.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('/') }}/assets/images/logo-dark.png" alt="" height="16">
                    </span>
                </a>
            </div>

            <ul class="list-unstyled topnav-menu topnav-menu-left mb-0">
                <li>
                    <button class="button-menu-mobile disable-btn waves-effect">
                        <i class="fe-menu"></i>
                    </button>
                </li>

                <li>
                    <h4 class="page-title-main">@yield('page-title')</h4>
                </li>

            </ul>

            <div class="clearfix"></div>

        </div>
        <!-- end Topbar -->

        <!-- ========== Left Sidebar Start ========== -->
        <div class="left-side-menu">

            <div class="h-100" data-simplebar>

                <!-- User box -->
                <div class="user-box text-center">

                    <img src="{{ asset('/') }}/assets/images/users/user-1.jpg" alt="user-img" title="Mat Helme"
                        class="rounded-circle img-thumbnail avatar-md">
                    <div class="dropdown">
                        <a href="#" class="user-name dropdown-toggle h5 mt-2 mb-1 d-block"
                            data-bs-toggle="dropdown" aria-expanded="false">{{ Auth::user()->name }}</a>
                    </div>

                    <p class="text-muted left-user-info">{{ Auth::user()->role->display_name }}</p>
                </div>

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <ul id="side-menu">
                        <li class="menu-title">Navigation</li>
                        <li>
                            <a href="{{ route('home') }}">
                                <i class="mdi mdi-view-dashboard-outline"></i>
                                <span class="badge bg-success rounded-pill float-end">9+</span>
                                <span> Dashboard </span>
                            </a>
                        </li>
                        <li class="menu-title mt-2">Apps</li>
                        @if (Auth::user()->role->name == 'admin')
                            @include('layouts.sidebar.admin')
                        @elseif(Auth::user()->role->name == 'lecturer')
                            @include('layouts.sidebar.lecturer')
                        @elseif(Auth::user()->role->name == 'student')
                            @include('layouts.sidebar.student')
                        @endif
                    </ul>
                </div>
                <!-- End Sidebar -->
                <div class="clearfix"></div>
            </div>
            <!-- Sidebar -left -->
        </div>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->
        <div class="content-page">
            <div class="content">
                <!-- Start Content-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div> <!-- container -->
            </div> <!-- content -->
            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> &copy; Programming Self Assesment by <a href="">Hafiz Putra
                                Pratama</a>
                        </div>

                    </div>
                </div>
            </footer>
            <!-- end Footer -->
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <div class="rightbar-overlay"></div>

    <!-- Vendor -->
    <script src="{{ asset('/') }}/assets/libs/jquery/jquery.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/node-waves/waves.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/feather-icons/feather.min.js"></script>

    <!-- third party js -->
    <script src="{{ asset('/') }}/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/pdfmake/build/pdfmake.min.js"></script>
    <script src="{{ asset('/') }}/assets/libs/pdfmake/build/vfs_fonts.js"></script>
    <!-- third party js ends -->
    <!-- Datatables init -->
    <script src="{{ asset('/') }}/assets/js/pages/datatables.init.js"></script>

    <!-- Toastr js -->
    <script src="{{ asset('/') }}assets/libs/toastr/build/toastr.min.js"></script>

    <script src="{{ asset('/') }}assets/js/pages/toastr.init.js"></script>
    <!-- App js -->
    <script src="{{ asset('/') }}/assets/js/app.min.js"></script>
    @if (session()->has('success'))
        <script>
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr["success"]("{{ session('success') }}");
        </script>
    @elseif (session()->has('error'))
        <script>
            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }
            toastr["error"]("{{ session('error') }}");
        </script>
    @endif
    @livewireScripts

</body>

</html>
