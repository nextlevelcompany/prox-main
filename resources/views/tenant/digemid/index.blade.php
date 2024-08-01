@extends('tenant.layouts.app')

@section('content')
    <tenant-digemid-index
        type="{{ $type ?? '' }}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
    ></tenant-digemid-index>

@endsection
