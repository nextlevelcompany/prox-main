@extends('tenant.layouts.app')

@section('content')

<tenant-subscription-payments-index
    :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    :date="'{{Carbon\Carbon::now()->format('Y-m-d')}}'"
></tenant-subscription-payments-index>

@endsection
