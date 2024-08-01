@extends('tenant.layouts.app')

@section('content')

    <tenant-full-subscription-plans-index
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :date="'{{Carbon\Carbon::now()->format('Y-m-d')}}'"
    ></tenant-full-subscription-plans-index>




@endsection
