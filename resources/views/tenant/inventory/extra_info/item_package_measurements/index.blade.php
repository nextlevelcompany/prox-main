@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-package-measurements
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-package-measurements>

@endsection
