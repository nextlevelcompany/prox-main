@extends('tenant.layouts.app')

@section('content')

    <tenant-report-documents-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-report-documents-index>

@endsection
