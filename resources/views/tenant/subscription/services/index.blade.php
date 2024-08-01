@extends('tenant.layouts.app')

@section('content')

    <tenant-items-index
        type="{{ 'ZZ' }}"
        :configuration="{{\Modules\Company\Models\Configuration::first()->toJson()}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
    ></tenant-items-index>

    <!--
    <tenant-subscription-service-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-subscription-service-index>

    -->

@endsection
