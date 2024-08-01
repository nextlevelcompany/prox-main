@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatches-create
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :auth-user="{{json_encode(Auth::user()->getDataOnlyAuthUser())}}"
    ></tenant-dispatches-create>
@endsection
