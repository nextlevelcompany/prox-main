<!DOCTYPE html>
@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];
    $visual->sidebar_theme = property_exists($visual, 'sidebar_theme')?$visual->sidebar_theme:''
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    data-layout="vertical"
    data-topbar="light"
    data-sidebar="dark"
    data-sidebar-size="lg"
    data-sidebar-image="none"
    data-preloader="disable"
    data-layout-mode="light"
    data-layout-width="fluid"
    data-layout-position="fixed"
    data-layout-style="default"
    data-sidebar-visibility="show"
    data-view-pos="{{$path[0] == 'pos'?'is_pos':'false'}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $vc_company->title_web }}</title>
    <meta name="googlebot" content="noindex">
    <meta name="robots" content="noindex">
    @if ($vc_company->favicon)
        <link rel="shortcut icon" type="image/png" href="{{ asset($vc_company->favicon) }}"/>
    @endif
    @if (file_exists(public_path('theme/custom_styles.css')))
        <link rel="stylesheet" href="{{ asset('theme/custom_styles.css') }}" />
    @endif
    {{-- @if($vc_compact_sidebar->skin)
        @if (file_exists(storage_path('app/public/skins/'.$vc_compact_sidebar->skin->filename)))
            <link rel="stylesheet" href="{{ asset('storage/skins/'.$vc_compact_sidebar->skin->filename) }}" />
        @endif
    @endif --}}
    <!-- Styles -->
    @yield('css')
    <!-- Layout config Js -->
    <script src="{{ asset('js/layout.js') }}"></script>
    <!-- Bootstrap Css -->
    <link href="{{ URL::asset('css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ URL::asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ mix('css/app.css') }}" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body class="">
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('tenant.layouts.partials.header')
        @include('tenant.layouts.partials.sidebar')
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
            {{-- @include('tenant.layouts.partials.footer') --}}
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @include('tenant.layouts.partials.customizer')

    <!-- JAVASCRIPT -->
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
