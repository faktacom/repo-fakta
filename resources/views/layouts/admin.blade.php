<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/img/logo-fakta-title.png')}}">
    <title>Fakta Admin - @yield('title')</title>
    <!-- This page CSS -->
    <!-- chartist CSS -->
    <link href="{{asset('assets/node_modules/morrisjs/morris.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/chartist.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <!--Toaster Popup message CSS -->
    <link href="{{asset('assets/node_modules/toast-master/css/jquery.toast.css')}}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{{asset('dist/css/style.min.css')}}" rel="stylesheet">
    <!-- Dashboard 1 Page CSS -->
    <link href="{{asset('dist/css/pages/dashboard1.css')}}" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    @stack('css')

    <style>
        .light-logo {
            height: 70%;
            width: 70%;
        }

        .krp-detail-user-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .krp-user-list-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        .krp-user-list-image-admin {
            width: 30px;
            height: 30px;
            object-fit: cover;
        }

        .krp-card-table-filter {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .krp-card-table {
            display: flex;
            justify-content: space-between;
        }

        /* .krp-detail-user-image img {
        width: 100%;
        height: 100%;
    } */

        .ct-series-a .ct-line,
        .ct-series-a .ct-point {
            stroke: #b81f24;
        }

        .ct-series-a .ct-slice-pie {
            fill: #0078D7;
            stroke: #0078D7;
        }

        .ct-series-b .ct-slice-pie {
            fill: #36415E;
            stroke: #36415E;
        }

        .ct-series-c .ct-slice-pie {
            fill: #DD4814;
            stroke: #DD4814;
        }

        .ct-series-d .ct-slice-pie {
            fill: #A4C639;
            stroke: #A4C639;
        }

        .ct-series-e .ct-slice-pie {
            fill: #9D9CB0;
            stroke: #9D9CB0;
        }

        .table-link-custom {
            color: var(--color-black);
        }

        .table-link-custom:hover {
            color: #2AA2D5;
        }
    </style>
</head>

<body class="skin-blue fixed-layout">
    <script>
        function replaceMissingImage(target, image){
            target.onerror = null;
            target.src = `{{asset('${image}')}}`;
        }
    </script>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" style="background-color: #fff;">
            <nav class="navbar top-navbar navbar-expand-md" style="box-shadow: 1px 0 20px rgb(0 0 0 / 8%);">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header" style="background-color: #fff; text-align: center;">
                    <a class="navbar-brand" href="{{route('admin.home')}}">
                        <img src="{{asset('assets/img/logo-fakta.png')}}" class="light-logo" alt="homepage" />
                    </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-md-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu" style="color: #b81f24;"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- User Profile -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown u-pro">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark profile-pic" style="color: #b81f24;" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (Auth::user()->profile_picture == NULL)
                                <img src="{{asset('assets/images/profile/no-profile.jpg')}}" alt="user" class="krp-user-list-image-admin">
                                @else
                                <img src="{{asset('assets/images/profile/' . Auth::user()->profile_picture)}}" alt="user" class="krp-user-list-image-admin">
                                @endif
                                <span class="hidden-md-down">{{Auth::user()->username}} &nbsp;<i class="fa fa-angle-down"></i></span> </a>
                            <div class="dropdown-menu dropdown-menu-right animated flipInY">
                                <!-- text-->
                                <a href="{{route('admin.profile')}}" class="dropdown-item"><i class="ti-settings"></i>
                                    Account Setting</a>
                                <!-- text-->
                                <div class="dropdown-divider"></div>
                                <!-- text-->
                                <a href="{{route('logout')}}" class="dropdown-item" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a>
                                <!-- text-->
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        {{-- <li class="user-pro"> <a class="has-arrow waves-effect waves-dark border-bottom"
                                href="javascript:void(0)" aria-expanded="false">
                                @if (Auth::user()->profile == NULL)
                                <img src="{{asset('assets/images/users/1.jpg')}}" alt="user-img" class="img-circle">
                        @else
                        <img src="{{asset('assets/images/profile/' . Auth::user()->profile)}}" alt="user-img" class="img-circle">
                        @endif
                        <span class="hide-menu">{{Auth::user()->name}}</span></a>
                        <ul aria-expanded="false" class="collapse">
                            <li><a href="{{route('admin.profile')}}"><i class="ti-settings"></i> Account Setting</a>
                            </li>
                            <li><a href="{{route('logout')}}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                        </li>
                        @if (Auth::user()->role_id == 1)
                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.maintenance.index')}}"><i class="icon-speedometer"></i><span>Maintenance
                            </a>
                        </li>
                        @endif

                        <li class="nav-small-cap">--- PERSONAL</li> --}}
                        <li><a class="waves-effect waves-dark border-bottom" href="{{route('admin.home')}}"><i class="icon-home"></i><span>Dashboard
                            </a>
                        </li>
                        <li><a class="has-arrow waves-effect waves-dark border-bottom" href="javascript:void(0)" aria-expanded="false"><i class=" icon-folder"></i><span class="hide-menu">Post</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{route('admin.headline.index')}}">Headline</a></li>
                                <li><a href="{{route('admin.news.index')}}">News</a></li>
                                <li><a href="{{route('admin.category.index')}}">Kompartemen</a></li>
                                <li><a href="{{route('admin.tag.index')}}">Tag</a></li>
                            </ul>
                        </li>
                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.webinar.index')}}">
                                <i class="icon-film"></i><span>Webinar
                            </a>
                        </li>

                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.community.index')}}"><i class="icon-user-following"></i><span>Community
                            </a>
                        </li>
                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.bank.index')}}"><i class="icon-camera"></i><span>Bank Image
                            </a>
                        </li>
                        {{-- <li><a class="has-arrow waves-effect waves-dark border-bottom" href="javascript:void(0)" aria-expanded="false"><i class=" icon-camera"></i><span class="hide-menu">Media</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{route('admin.video.index')}}">Video</a></li>
                                <li><a href="{{route('admin.image.index')}}">Image</a></li>
                            </ul>
                        </li> --}}
                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.footer.index')}}"><i class="icon-drawar"></i><span>Footer Link
                            </a>
                        </li>

                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.ads.index')}}"><i class="icon-feed"></i><span>Ads
                            </a>
                        </li>

                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.comment.index')}}"><i class="icon-bubbles"></i><span>Comment
                            </a>
                        </li>
                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.user.perform')}}"><i class="icon-speedometer"></i><span>User Performance
                            </a>
                        </li>
                        <li> <a class="waves-effect waves-dark border-bottom" href="{{route('admin.survey.index')}}"><i class="icon-eye"></i><span>Survey
                            </a>
                        </li>
                        <li><a class="has-arrow waves-effect waves-dark border-bottom" href="javascript:void(0)" aria-expanded="false"><i class=" icon-people"></i><span class="hide-menu">User
                                    Management</span></a>
                            <ul aria-expanded="false" class="collapse">
                                <li><a href="{{route('admin.user.index')}}">User</a></li>
                                <li><a href="{{route('admin.uac')}}">Roles</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                @yield('content')

                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->

                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
        
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="{{asset('assets/node_modules/jquery/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/sweetalert2.all.min.js') }}"></script>
    <!-- Bootstrap popper Core JavaScript -->
    <script src="{{asset('assets/node_modules/popper/popper.min.js')}}"></script>
    <script src="{{asset('assets/node_modules/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{asset('dist/js/perfect-scrollbar.jquery.min.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('dist/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('dist/js/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('dist/js/custom.min.js')}}"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="{{asset('assets/node_modules/raphael/raphael-min.js')}}"></script>
    <script src="{{asset('assets/node_modules/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!-- Popup message jquery -->
    <script src="{{asset('assets/node_modules/toast-master/js/jquery.toast.js')}}"></script>
    <!-- Chart JS -->
    {{-- <script src="{{asset('dist/js/dashboard1.js')}}"></script> --}}
    {{-- <script src="{{asset('assets/node_modules/toast-master/js/jquery.toast.js')}}"></script> --}}
    @stack('js')
</body>

</html>