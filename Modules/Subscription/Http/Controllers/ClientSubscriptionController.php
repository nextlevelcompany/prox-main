<?php

namespace Modules\Subscription\Http\Controllers;

use App\Helpers\SearchCustomerHelper;
use Modules\Company\Models\Configuration;
use Modules\Person\Http\Controllers\PersonController;
use Modules\Person\Http\Requests\PersonRequest;
use Modules\Subscription\Http\Resources\PersonCollection;
use Modules\Catalog\Models\Country;
use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\District;
use Modules\Catalog\Models\IdentityDocumentType;
use Modules\Catalog\Models\Province;
use Modules\Person\Models\PersonType;
use Illuminate\Http\Request;

class ClientSubscriptionController extends SubscriptionController
{
    /**
     * @return string[]
     */
    public function Columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número',
            'document_type' => 'Tipo de documento',
            // 'childrens' => 'Nombre de hijos',
        ];
    }

    public function Record(Request $request)
    {
        $personId = (int)($request->has('person') ? $request->person : 0);
        $records = SearchCustomerHelper::getCustomersToSubscriptionList($request, $personId);
        if ($request->has('users')) {
            if ($request->users == 'parent') {
                $records->where('parent_id', 0);
            } elseif ($request->users == 'children') {
                $records->where('parent_id', '!=', 0);
            }
        }
        $records = $records->firstOrFail();

        return ['data' => $records->getCollectionData(true, true)];
    }

    public function Records(Request $request)
    {
        $records = SearchCustomerHelper::getCustomersToSubscriptionList($request);
        // getCustomersToSubscriptionList(Request $request = null, ?int $id = 0, $onlyParent = true){
        if ($request->has('users')) {
            if ($request->users == 'parent') {
                $records->where('parent_id', 0);
            } elseif ($request->users == 'children') {
                $records->where('parent_id', '!=', 0);
            }
        }
        // users
        return new PersonCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function Tables()
    {
        $countries = func_get_table_countries();
        $departments = Department::whereActive()->orderByDescription()->get();
        $provinces = Province::whereActive()->orderByDescription()->get();
        $districts = District::whereActive()->orderByDescription()->get();
        $identity_document_types = IdentityDocumentType::whereActive()->get();
        $person_types = PersonType::get();
        $locations = $this->getLocationCascade();
        $api_service_token = Configuration::getApiServiceToken();

        return compact('countries', 'departments', 'provinces', 'districts', 'identity_document_types', 'locations', 'person_types', 'api_service_token');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        return view('tenant.subscription.clients.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function indexChildren()
    {
        return view('tenant.subscription.clients.index_child');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        return view('tenant.subscription.create');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('subscription::edit');
    }


    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        return view('subscription::show');
    }

    public function store(PersonRequest $request)
    {
        $request->validate([
            'childrens' => 'required|array'
        ]);
        //
        $personController = new PersonController();

        $data = $personController->store($request);
        $childrens = $request->childrens;

        $demo = [];
        foreach ($childrens as $child) {
            $child['parent_id'] = $data['id'];
            $child['addresses'] = $request->input('addresses');
            $childRequest = new PersonRequest();
            $childRequest->merge($child);
            $demo [] = $personController->store($childRequest);
        }
        $data[] = $demo;

        return $data;
    }
}
