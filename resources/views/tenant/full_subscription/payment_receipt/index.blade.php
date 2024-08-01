@extends('tenant.layouts.app')

@section('content')

    <tenant-full-subscription-index-payment-receipt
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :soap-company="{{ json_encode($soap_company) }}">
    </tenant-full-subscription-index-payment-receipt>

@endsection
