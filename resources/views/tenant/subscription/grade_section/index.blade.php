@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Grados y secciones</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                        <template>
                            <li class="breadcrumb-item active">Grados y secciones</li>
                        </template>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-6">
            <tenant-subscription-grades-index></tenant-subscription-grades-index>
        </div>
        <div class="col-12 col-sm-6">
            <tenant-subscription-sections-index></tenant-subscription-sections-index>
        </div>
    </div>
@endsection
