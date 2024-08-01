@extends('tenant.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <tenant-ledger-accounts
                :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
            ></tenant-ledger-accounts>
        </div>
    </div>

@endsection
