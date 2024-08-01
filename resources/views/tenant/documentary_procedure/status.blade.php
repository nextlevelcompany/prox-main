@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-status
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :status='@json($status)'
        :users='@json($users)'
    ></tenant-documentary-status>
@endsection
