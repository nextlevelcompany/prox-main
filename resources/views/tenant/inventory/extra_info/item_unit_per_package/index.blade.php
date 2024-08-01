@extends('tenant.layouts.app')

@section('content')
    <tenant-inventory-item-units-per-package-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-units-per-package-index>

@endsection
