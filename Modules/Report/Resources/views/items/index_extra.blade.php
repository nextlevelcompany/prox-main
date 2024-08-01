@extends('tenant.layouts.app')

@section('content')

    <tenant-report-items-extra-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    >

    </tenant-report-items-extra-index>

@endsection
