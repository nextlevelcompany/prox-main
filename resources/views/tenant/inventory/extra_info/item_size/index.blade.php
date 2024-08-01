@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-size-property
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-size-property>

@endsection
