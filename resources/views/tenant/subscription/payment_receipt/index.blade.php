@extends('tenant.layouts.app')

@section('content')

    <tenant-index-payment-receipt
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :soap-company="{{ json_encode($soap_company) }}">
        </tenant-index-payment-receipt>




@endsection
