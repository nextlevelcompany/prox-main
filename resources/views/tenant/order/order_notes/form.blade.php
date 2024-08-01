@extends('tenant.layouts.app')

@section('content')
    <tenant-order-notes-form
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :type-user="{{json_encode(Auth::user()->type)}}"
        ></tenant-order-notes-form>
@endsection
