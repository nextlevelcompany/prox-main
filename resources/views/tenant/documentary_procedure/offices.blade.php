@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-offices
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :etapas='@json($stages)'
        :users='@json($users)'
    ></tenant-documentary-offices>
@endsection
