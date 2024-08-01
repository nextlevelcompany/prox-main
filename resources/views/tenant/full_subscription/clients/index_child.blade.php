@extends('tenant.layouts.app')

@section('content')

    <tenant-full-subscription-client-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :listtype="'children'"
    ></tenant-full-subscription-client-index>

@endsection
