@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-create
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :order_form_id="{{ json_encode($order_form_id) }}"
    ></tenant-dispatches-create>
@endsection
