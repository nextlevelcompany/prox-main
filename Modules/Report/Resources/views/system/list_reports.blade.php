@extends('system.layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Reportes</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <li class="breadcrumb-item active">Reportes</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <!-- General -->
        <div class="col-6 col-md-4 mb-4">
            <div class="card card-dashboard card-reports">
                <div class="card-body">
                    <h6 class="card-title">General</h6>
                    <ul class="card-report-links">
                        <li>
                            <a href="{{route('system.report_login_lockout.index')}}">
                                Cuentas bloquedas
                            </a>
                        </li>
                        <li>
                            <a href="{{route('system.user_not_change_password.index')}}">
                                Usuarios con contraseña desactualizada
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
