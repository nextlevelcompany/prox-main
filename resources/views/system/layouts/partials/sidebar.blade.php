@php
    $path = explode('/', request()->path());
    $path[1] = (array_key_exists(1, $path)> 0)?$path[1]:'';
    $path[2] = (array_key_exists(2, $path)> 0)?$path[2]:'';
    $path[0] = ($path[0] === '')?'documents':$path[0];
@endphp
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        {{-- <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('logo/logo.jpg') }}" alt="" height="17">
            </span>
        </a> --}}
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="fs-2 ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                {{-- <li class="menu-title"><span>@lang('translation.menu')</span></li> --}}
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ (in_array($path[0], ['clients', 'dashboard']))?'active':'' }}"
                        href="{{route('system.dashboard')}}">
                        <i class="fs-2 ri-dashboard-2-line"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ ($path[0] === 'plans')?'active':'' }}"
                        href="{{route('system.plans.index')}}">
                        <i class="fs-2 ri-honour-line"></i> <span>Planes</span>
                    </a>
                </li>
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ ($path[0] === 'accounting')?'active':'' }}"
                        href="{{route('system.accounting.index')}}">
                        <i class="fs-2 ri-calculator-fill"></i> <span>Contabilidad</span>
                    </a>
                </li>
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ ($path[0] === 'configurations' && !$path[1])?'active':'' }}"
                        href="{{route('system.configuration.index')}}">
                        <i class="fs-2 ri-settings-3-fill"></i> <span>Configuracion</span>
                    </a>
                </li>
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ ($path[0] === 'changelog')?'active':'' }}"
                        href="{{route('system.update')}}">
                        <i class="fs-2 ri-git-repository-commits-fill"></i> <span>Historial de cambios</span>
                    </a>
                </li>
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ ($path[0] === 'backup')?'active':'' }}"
                        href="{{route('system.backup')}}">
                        <i class="fs-2 ri-folder-download-fill"></i> <span>Backup</span>
                    </a>
                </li>
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ ($path[1] === 'information')?'active':'' }}"
                        href="{{route('system.information')}}">
                        <i class="fs-2 ri-server-fill"></i> <span>Información</span>
                    </a>
                </li>
                <li class="nav-item py-2">
                    <a class="nav-link menu-link {{ ($path[0] === 'reports')?'active':'' }}"
                        href="{{route('system.list-reports')}}">
                        <i class="fs-2 ri-file-text-fill"></i> <span>Reportes</span>
                    </a>
                </li>
                {{-- <li class="nav-item py-2">
                    <a class="nav-link menu-link "
                        href="{{url('docs')}}"
                        target="_BLANK">
                        <i class="fs-2 ri-book-open-fill"></i> <span>Wiki</span>
                    </a>
                </li> --}}
                <li class="nav-item py-2">
                    <a class="nav-link menu-link "
                        href="{{url('logs')}}"
                        target="_BLANK">
                        <i class="fs-2 ri-file-damage-fill"></i> <span>Logs</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
