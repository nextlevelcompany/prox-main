@extends('tenant.layouts.app')

@section('content')
    <tenant-dispatch_carrier-form
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
        :auth-user="{{json_encode(Auth::user()->getDataOnlyAuthUser())}}"
    ></tenant-dispatch_carrier-form>
@endsection
