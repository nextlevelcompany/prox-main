@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-color-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-color-index>

@endsection
