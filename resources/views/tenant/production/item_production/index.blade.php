@extends('tenant.layouts.app')

@section('content')
    <tenant-item-sets-index
        :type-user="{{json_encode(Auth::user()->type)}}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-item-sets-index>
@endsection
