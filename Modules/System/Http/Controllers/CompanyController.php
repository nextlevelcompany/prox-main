<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\System\Http\Requests\CompanyRequest;
use Modules\System\Models\Configuration;
use Modules\Company\Models\SoapType;
use Modules\System\Http\Resources\CompanyResource;

class CompanyController extends Controller
{
    public function create()
    {
        return view('tenant.companies.form');
    }

    public function tables()
    {
        $soap_sends = config('tables.system.soap_sends');
        $soap_types = SoapType::all();

        return compact('soap_types', 'soap_sends');
    }

    public function record()
    {
        $configuration = Configuration::first();
        $record = new CompanyResource($configuration);
        return $record;
    }

    public function store(CompanyRequest $request)
    {
        $company = Configuration::first();
        $company->fill($request->all());
        $company->save();

        return [
            'success' => true,
            'message' => 'Empresa actualizada'
        ];
    }
}
