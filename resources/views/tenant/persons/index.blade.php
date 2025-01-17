@extends('tenant.layouts.app')

@section('content')

    <tenant-persons-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :type="{{ json_encode($type) }}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-persons-index>


@endsection
