@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-item-status
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-item-status>

@endsection
