@extends('tenant.layouts.app')

@section('content')

    <tenant-sale-notes-index
        :soap-company="{{ json_encode($soap_company) }}"
        :permission-edit="{{ json_encode(auth()->user()->permission_edit_sale_note) }}"
        :type-user="{{ json_encode(auth()->user()->type) }}"
        :configuration="{{\Modules\Company\Models\Configuration::getPublicConfig()}}"
    ></tenant-sale-notes-index>
@endsection
