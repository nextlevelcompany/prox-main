@extends('tenant.layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Empresa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active">Empresa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-12 pt-2 pt-md-0">
            <tenant-companies-form></tenant-companies-form>
        </div>
        <div class="col-lg-6 col-md-12">
            <tenant-certificates-index></tenant-certificates-index>
            <tenant-signature-pse-index></tenant-signature-pse-index>
            <tenant-whatsapp-api-index></tenant-whatsapp-api-index>
            <tenant-payment-configurations-index></tenant-payment-configurations-index>
        </div>
    </div>
@endsection
