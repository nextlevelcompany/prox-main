@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-product-family
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-product-family>

@endsection
