@extends('tenant.layouts.app')

@section('content')

    <tenant-purchases-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-purchases-index>

@endsection
