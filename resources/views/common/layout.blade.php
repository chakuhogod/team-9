@extends("common.base")

@section("base_content")

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="/" class="logo">Bank<b>Linq</b></a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">

        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">

            <!--
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="{{ asset("/bower_components/AdminLTE/dist/img/user2-160x160.jpg") }}" class="img-circle" alt="User Image" />
                </div>
                <div class="pull-left info">
                    <p>Alexander Pierce</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>-->

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">NAVIGATION</li>
                <!-- Optionally, you can add icons to the links -->
                <li class="{{ Request::is('dashboard') || Request::is('/') || Request::is('') ? 'active' : '' }}"><a href="/dashboard"><span>Dashboard</span></a></li>
                <li class="{{ Request::is('bookkeeping') ? 'active' : '' }}"><a href="/bookkeeping"><span>Bookkeeping</span></a></li>
                <li class="{{ Request::is('charts') ? 'active' : '' }}"><a href="/charts"><span>Charts</span></a></li>
            </ul><!-- /.sidebar-menu -->
        </section>
        <!-- /.sidebar -->
    </aside>

    <selection id="content_wrapper">
        @yield("body")
    </selection>

@stop