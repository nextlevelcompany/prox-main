@extends('tenant.layouts.app')

@section('content')
    <tenant-order-forms-form
        :id="{{ json_encode($id) }}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-order-forms-form>
@endsection
