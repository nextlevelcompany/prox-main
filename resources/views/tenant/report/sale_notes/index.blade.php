@extends('tenant.layouts.app')

@section('content')

    <tenant-report-sale_notes-index
            :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
            >
    </tenant-report-sale_notes-index>

@endsection
