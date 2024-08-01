@extends('tenant.layouts.app')

@section('content')

    <tenant-account-format
        :currencies="{{json_encode($currencies)}}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-account-format>

@endsection
