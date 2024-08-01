@extends('tenant.layouts.app')

@section('content')

    <tenant-documents-note
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :document="{{ json_encode($document) }}"></tenant-documents-note>

@endsection
