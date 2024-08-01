@extends('tenant.layouts.app')

@section('content')

    <tenant-mitiendape-config
        :establishments="{{json_encode($establishments )}}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-order-notes-index>

@endsection
