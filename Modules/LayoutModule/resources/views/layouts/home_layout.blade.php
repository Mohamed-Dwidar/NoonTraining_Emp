<!DOCTYPE html>
<html lang="ar" dir="rtl" data-textdirection="rtl" class="loading">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="نظام الاختبارات - معهد نون للتدريب">
    <meta name="keywords" content="اختبارات, معهد نون, تدريب">
    <meta name="author" content="Noon Training Center">
    <title>مرحباً بك في منصة الاختبارات المحوسبة - معهد نون للتدريب</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/bootstrap.css') }}">
    <!-- font icons-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/icomoon.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/fonts/flag-icon-css/css/flag-icon.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/vendors/css/extensions/pace.css') }}">
    <!-- END VENDOR CSS-->
    <!-- BEGIN ROBUST CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/bootstrap-extended.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/colors-admin.css') }}">
    <!-- END ROBUST CSS-->
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css-rtl/core/menu/menu-types/vertical-menu.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('admin-assets/css-rtl/core/menu/menu-types/vertical-overlay-menu.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/pages/login-register.css') }}">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('admin-assets/css-rtl/app.css') }}">
    <!-- END Custom CSS-->

</head>

<body>
    <div class="intro-container">
        <!-- Animated particles -->
        <div class="particles">
            <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
            <div class="particle" style="left: 20%; animation-delay: 1s;"></div>
            <div class="particle" style="left: 30%; animation-delay: 2s;"></div>
            <div class="particle" style="left: 40%; animation-delay: 0.5s;"></div>
            <div class="particle" style="left: 50%; animation-delay: 1.5s;"></div>
            <div class="particle" style="left: 60%; animation-delay: 2.5s;"></div>
            <div class="particle" style="left: 70%; animation-delay: 0.8s;"></div>
            <div class="particle" style="left: 80%; animation-delay: 1.8s;"></div>
            <div class="particle" style="left: 90%; animation-delay: 2.8s;"></div>
        </div>

        <div class="main-content">
            @yield('content')
        </div>
    </div>

    <!-- BEGIN VENDOR JS-->
    <script src="{{ asset('admin-assets/js/core/libraries/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/tether.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/js/core/libraries/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/perfect-scrollbar.jquery.min.js') }}" type="text/javascript">
    </script>
    <script src="{{ asset('admin-assets/vendors/js/ui/unison.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/blockUI.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/jquery.matchHeight-min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/ui/screenfull.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/vendors/js/extensions/pace.min.js') }}" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN ROBUST JS-->
    <script src="{{ asset('admin-assets/js/core/app-menu.js') }}" type="text/javascript"></script>
    <script src="{{ asset('admin-assets/js/core/app.js') }}" type="text/javascript"></script>
    <!-- END ROBUST JS-->
</body>

</html>
