<?php use Illuminate\Support\Facades\Auth;?>
<body class="fixed-left">
    <!-- Start wrapper -->
    <div id="wrapper">
        <div class="topbar">
            <!-- LOGO -->
            <div class="topbar-left">
                <div class="text-center"> <a href="#" class="logo"><img src="{{ asset('admin/assets/images/logo.png') }}" alt=""></a>
                </div>
            </div>

            <div class="navbar navbar-default" role="navigation">
                <div class="container">
                    <div class="">

                        <div class="pull-left">
                            <button class="button-menu-mobile open-left"> <i class="fa fa-bars"></i> </button>
                        </div>

                        <ul class="nav navbar-nav navbar-right pull-right">
                            <li class="dropdown"> <a href="#" class="dropdown-toggle profile"
                                    data-toggle="dropdown" aria-expanded="true">
                                    <img src="{{ asset('admin/assets/images/avatar.jpg') }}" alt="user-img" class="img-circle"> </a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{ route('change.password') }}"><i class="fa fa-unlock-alt"
                                                aria-hidden="true"></i> Change Password</a></li>
                                    <li><a href="{{ route('change.email') }}"><i class="fa fa-envelope eml"
                                                aria-hidden="true"></i>Change Email</a></li>
                                    <li><a href="{{ route('admin.logout') }}"><i class="fa fa-power-off" aria-hidden="true"></i>
                                            Logout</a></li>
                                </ul>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </div>
