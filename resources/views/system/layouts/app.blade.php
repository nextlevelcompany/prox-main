<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-layout="vertical"
    data-topbar="light"
    data-sidebar="dark"
    data-sidebar-size="lg"
    data-sidebar-image="none"
    data-preloader="disable"
    data-layout-mode="{{$system_visual->data_layout_mode ?? ''}}"
    data-layout-width="fluid"
    data-layout-position="fixed"
    data-layout-style="default"
    data-sidebar-visibility="show">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="googlebot" content="noindex">
    <meta name="robots" content="noindex">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Facturación Electrónica</title>

    <!-- Styles -->
    @yield('css')
    <!-- Layout config Js -->
    <script src="{{ asset('js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ URL::asset('css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ mix('css/app.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    {{-- <link href="{{ URL::asset('css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" /> --}}
</head>
<body class="">
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('system.layouts.partials.topbar')
        @include('system.layouts.partials.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            {{-- @include('system.layouts.partials.footer') --}}
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ URL::asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ URL::asset('libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ URL::asset('libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ URL::asset('libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ URL::asset('js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
    {{-- <script src="{{ URL::asset('js/plugins.js') }}"></script> --}}
    @yield('script')
    <script src="{{ asset('js/template.js') }}"></script>
</body>
</html>
