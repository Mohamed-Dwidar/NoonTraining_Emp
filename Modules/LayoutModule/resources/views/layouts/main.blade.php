<!DOCTYPE html>
<html lang="en" data-textdirection="ltr" class="loading">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <title>لوحه تحكم الإدارة | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/bootstrap.css') }}">
    <!-- font icons-->
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/icomoon.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css"
                href="{{ asset('admin-assets/fonts/flag-icon-css/css-rtl/flag-icon.min.css') }}"> --}}
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css-rtl/extensions/pace.css') }}">
        --}}
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/colors.css') }}">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css-rtl/core/menu/menu-types/vertical-overlay-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/core/colors/palette-gradient.css') }}">
    <!-- END Page Level CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/vendors/css/pickers/pickadate/pickadate.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Font Awesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-1ycn6IcaQQ40/MKB4Imkb9l9+gqV0MZoHLM2lSXZ1rtB8F7R9E5yNEdaJv0J8B+Dm/o9H9H8w+P6JvJzk5Fyw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" type="text/css" href="{{ asset('css/style-admin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/colors-admin.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('admin-assets/fonts/fontawesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/custom-rtl.css') }}">

    @stack('styles')
</head>

<body data-open="click" data-menu="horizontal-menu" data-col="2-columns"
    class="horizontal-layout horizontal-menu 2-columns fixed-navbar">

    @include('layoutmodule::layouts.header')

    @if (Auth::guard('admin')->check())
        @include('layoutmodule::admin.nav')
    @elseif (Auth::guard('student')->check())
        @include('layoutmodule::student.nav')
    @else
        @include('layoutmodule::user.nav')
    @endif

    <div class="app-content content container-fluid">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    @include('layoutmodule::layouts.footer')

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('admin-assets/js/core/libraries/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/tether.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/js/core/libraries/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/perfect-scrollbar.jquery.min.js') }}" type="text/javascript">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/unison.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/blockUI.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/jquery.matchHeight-min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/screenfull.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/extensions/pace.min.js') }}" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="{{ asset('admin-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN ROBUST JS-->
    <script src="{{ asset('admin-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/js/core/app.js') }}" type="text/javascript"></script>
    <!-- END ROBUST JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <!-- END PAGE LEVEL JS-->

    <script src="{{ asset('admin-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
    <script src="{{ asset('admin-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
    {{-- <script src="{{ asset('admin-assets/vendors/js/pickers/datetime/picker.time.js') }}"></script> --}}


    @stack('scripts')
    <script src="{{ asset('admin-assets/js/custom.js') }}" type="text/javascript"></script>
</body>

</html>
