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
                    @yield('sidebar')
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
