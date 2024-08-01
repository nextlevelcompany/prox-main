@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-units-business
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-units-business>

@endsection
