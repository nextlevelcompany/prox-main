@extends('tenant.layouts.app')

@section('content')

    <tenant-bankloans-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-bankloans-index>

@endsection
