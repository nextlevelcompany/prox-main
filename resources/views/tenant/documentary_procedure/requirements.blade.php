@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-requirements
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :requirements='@json($requirements_list)'
    ></tenant-documentary-requirements>
@endsection
