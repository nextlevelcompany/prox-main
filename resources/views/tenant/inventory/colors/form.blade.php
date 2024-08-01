@extends('tenant.layouts.app')

@section('content')

    <tenant-inventory-devolutions-form
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        ></tenant-inventory-devolutions-form>

@endsection
