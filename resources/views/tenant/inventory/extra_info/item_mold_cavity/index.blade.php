@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-mold-cavities
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-inventory-mold-cavities>

@endsection
