@extends('tenant.layouts.app')

@section('content')

    <tenant-subscription-plans-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :date="'{{Carbon\Carbon::now()->format('Y-m-d')}}'"
    ></tenant-subscription-plans-index>




@endsection
