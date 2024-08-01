@extends('tenant.layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-sm-6">
        <tenant-ecommerce-configuration-info></tenant-ecommerce-configuration-info>
        <tenant-ecommerce-configuration-culqi></tenant-ecommerce-configuration-culqi>
        <tenant-ecommerce-configuration-paypal></tenant-ecommerce-configuration-paypal>
    </div>
    <div class="col-12 col-sm-6">
        <tenant-ecommerce-configuration-logo></tenant-ecommerce-configuration-logo>
        <tenant-ecommerce-configuration-social></tenant-ecommerce-configuration-social>
        <tenant-ecommerce-configuration-tag></tenant-ecommerce-configuration-tag>
    </div>
</div>
@endsection

