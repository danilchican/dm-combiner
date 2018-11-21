<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.general.head')
</head>
<body class="nav-md">
<div id="app">
    <div class="container body">
        <div class="main_container">
            @include('partials.general.header')

            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    @yield('sidebar_header')

                    <div class="clearfix"></div>
                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            @yield('sidebar_menu')
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>
            <!-- page content -->
            <div class="right_col" role="main">
                @yield('content')
            </div>
            <!-- /page content -->
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
@yield('scripts')
</body>
</html>
