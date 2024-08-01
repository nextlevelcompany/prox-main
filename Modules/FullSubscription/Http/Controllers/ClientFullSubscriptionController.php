<?php

namespace Modules\FullSubscription\Http\Controllers;

use App\Helpers\SearchCustomerHelper;
use Modules\Catalog\Models\Country;
use Modules\Catalog\Models\Department;
use Modules\Catalog\Models\District;
use Modules\Catalog\Models\IdentityDocumentType;
use Modules\Catalog\Models\Province;
use Modules\Company\Models\Configuration;
use Modules\Person\Http\Controllers\PersonController;
use Modules\Person\Http\Requests\PersonRequest;
use Modules\Person\Models\PersonType;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\FullSubscription\Http\Resources\SubscriptionPersonCollection;
use Modules\FullSubscription\Models\FullSubscriptionServerDatum;
use Modules\FullSubscription\Models\FullSubscriptionUserDatum;

class ClientFullSubscriptionController extends FullSubscriptionController
{
    public function Columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número',
            'document_type' => 'Tipo de documento',
            'discord_channel' => 'Canal',
            'telephone' => 'Teléfono',
        ];
    }

    public function Record(Request $request)
    {
        $personId = (int)($request->has('person') ? $request->person : 0);
        $records = SearchCustomerHelper::getCustomersToSubscriptionList($request, $personId)->firstOrFail();

        return ['data' => $records->getCollectionData(true, false, true)];
    }

    public function RecordServer(Request $request)
    {
        $personId = (int)($request->has('person') ? $request->person : 0);
        $records = FullSubscriptionServerDatum::find($personId);

        return ['data' => $records];
    }

    public function Records(Request $request)
    {
        $records = SearchCustomerHelper::getCustomersToSubscriptionList($request);
        return new SubscriptionPersonCollection($records->paginate(config('tenant.items_per_page')));
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
        $configuration = Configuration::first();
        $configuration = $configuration->getCollectionData();
        // $api_service_token = $configuration->token_apiruc === 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;
        $api_service_token = Configuration::getApiServiceToken();

        return compact('countries',
            'departments',
            'provinces',
            'districts',
            'configuration',
            'identity_document_types',
            'locations',
            'person_types',
            'api_service_token'
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('full_subscription::create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        return view('full_subscription::edit');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('tenant.full_subscription.clients.index');
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Application|Factory|View
     */
    public function show($id)
    {
        return view('full_subscription::show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function indexChildren()
    {
        return view('tenant.full_subscription.clients.index_child');
    }

    public function store(PersonRequest $request)
    {
        $personController = new PersonController();

        $data = $personController->store($request);
        $servers = ($request->has('servers')) ? $request->servers : null;
        $demo = [];
        $person_id = $data['id'];
        if (!empty($servers)) {
            foreach ($servers as $server) {
                $server_data = (isset($server['id'])) ?
                    FullSubscriptionServerDatum::find($server['id']) :
                    new FullSubscriptionServerDatum($server);
                $server_data
                    ->setPersonId($person_id)
                    ->setHost($server['host'] ?? null)
                    ->setIp($server['ip'] ?? null)
                    ->setUser($server['user'] ?? null)
                    ->setPassword($server['password'] ?? null)
                    ->push();

            }
        }
        $extra_fields = [];

        $extra_data_req = $request->all();
        $extra_fields['discord_user'] = $extra_data_req['discord_user'] ?? null;
        $extra_fields['slack_channel'] = $extra_data_req['slack_channel'] ?? null;
        $extra_fields['discord_channel'] = $extra_data_req['discord_channel'] ?? null;
        $extra_fields['gitlab_user'] = $extra_data_req['gitlab_user'] ?? null;
        $extra_data = FullSubscriptionUserDatum::where('person_id', $person_id)->first();
        if (empty($extra_data)) {
            $extra_data = new FullSubscriptionUserDatum($extra_fields);
        }
        $extra_data
            ->setPersonId($person_id)
            ->setDiscordUser($extra_fields['discord_user'])
            ->setSlackChannel($extra_fields['slack_channel'])
            ->setDiscordChannel($extra_fields['discord_channel'])
            ->setGitlabUser($extra_fields['gitlab_user'])
            ->push();

        $data[] = $demo;
        return $data;

    }


}
