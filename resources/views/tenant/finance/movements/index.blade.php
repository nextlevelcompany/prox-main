@extends('tenant.layouts.app')

@section('content')

    <tenant-finance-movements-index
        :ismovements="{{$isMovements}}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-finance-movements-index>

@endsection
