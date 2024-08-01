@extends('tenant.layouts.app')

@section('content')

    <tenant-users-index
        :type-user="{{ json_encode(auth()->user()->type) }}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        ></tenant-users-index>

@endsection
