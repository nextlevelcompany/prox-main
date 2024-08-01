@extends('tenant.layouts.app')

@section('content')

    <tenant-state-account-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-state-account-index>

@endsection
