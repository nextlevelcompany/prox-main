<?php

namespace Modules\Ecommerce\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Company\Models\Company;
use Modules\Ecommerce\Http\Requests\ConfigurationEcommerceRequest;
use Modules\Ecommerce\Http\Resources\ConfigurationEcommerceResource;
use Modules\Ecommerce\Models\ConfigurationEcommerce;
use Modules\Finance\Helpers\UploadFileHelper;

class ConfigurationController extends Controller
{
    public function index()
    {
        return view('tenant.ecommerce.ecommerce.configuration.index');
    }

    public function record()
    {
        $configuration = ConfigurationEcommerce::first();
        $record = new ConfigurationEcommerceResource($configuration);

        return $record;
    }

    public function store_configuration(ConfigurationEcommerceRequest $request)
    {
        $id = $request->input('id');
        $configuration = ConfigurationEcommerce::find($id);
        $configuration->fill($request->all());
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    public function store_configuration_culqui(Request $request)
    {
        $id = $request->input('id');
        $configuration = ConfigurationEcommerce::find($id);
        $configuration->fill($request->all());
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración Culqui actualizada'
        ];
    }

    public function store_configuration_paypal(Request $request)
    {
        $id = $request->input('id');
        $configuration = ConfigurationEcommerce::find($id);
        $configuration->fill($request->all());
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración Paypal actualizada'
        ];
    }

    public function store_configuration_tag(Request $request)
    {
        $id = $request->input('id');
        $configuration = ConfigurationEcommerce::find($id);
        $configuration->fill($request->all());
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración Tags actualizada'
        ];
    }

    public function store_configuration_social(Request $request)
    {
        $id = $request->input('id');
        $configuration = ConfigurationEcommerce::find($id);
        $configuration->fill($request->all());
        $configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración de Redes Sociales actualizada'
        ];
    }

    public function uploadFile(Request $request)
    {
        if ($request->hasFile('file')) {

            $config = ConfigurationEcommerce::first();
            $company = Company::first();

            $type = $request->input('type'); //logo_store

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = $type . '_' . $company->number . '.' . $ext;

            request()->validate(['file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            UploadFileHelper::checkIfValidFile($name, $file->getPathName(), true);

            $file->storeAs('public/uploads/logos', $name);

            $config->logo = $name;

            $config->save();

            return [
                'success' => true,
                'message' => __('app.actions.upload.success'),
                'name' => $name,
                'type' => $type
            ];
        }
        return [
            'success' => false,
            'message' => __('app.actions.upload.error'),
        ];
    }


}
