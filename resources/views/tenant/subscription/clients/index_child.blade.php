@extends('tenant.layouts.app')

@section('content')

    <tenant-subscription-client-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :listtype="'children'"
    ></tenant-subscription-client-index>

@endsection
