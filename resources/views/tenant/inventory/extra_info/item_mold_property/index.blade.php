@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-mold-property
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-mold-property>

@endsection
