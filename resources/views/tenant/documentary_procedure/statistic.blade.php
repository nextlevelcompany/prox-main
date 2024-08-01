@extends('tenant.layouts.app')

@section('content')
    <tenant-documentary-statistic
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-documentary-statistic>
@endsection
