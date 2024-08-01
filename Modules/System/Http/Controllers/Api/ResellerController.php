<?php

namespace Modules\System\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Hyn\Tenancy\Environment;
use Modules\System\Http\Controllers\ClientController;
use Modules\System\Http\Resources\ClientCollection;
use Modules\System\Models\Client;
use Modules\System\Models\Configuration;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResellerController extends Controller
{
    public function resellerDetail()
    {
        $records = Client::latest()->get();

        foreach ($records as &$row) {
            $tenancy = app(Environment::class);
            $tenancy->tenant($row->hostname->website);
            $row->count_doc = DB::connection('tenant')->table('documents')->count();
            $row->count_user = DB::connection('tenant')->table('users')->count();
        }

        return new ClientCollection($records);
    }

    public function login(Request $request)
    {
        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return [
                'success' => false,
                'message' => 'No Autorizado'
            ];
        }

        $user = $request->user();
        return [
            'success' => true,
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->api_token,
        ];
    }

    public function lockedAdmin(Request $request)
    {
        // dd($request->locked_admin);

        $configuration = Configuration::first();
        $configuration->locked_admin = $request->locked_admin;
        $configuration->save();


        $clients = Client::get();

        foreach ($clients as $client) {

            $client->locked_tenant = $configuration->locked_admin;
            $client->save();

            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);
            DB::connection('tenant')->table('configurations')->where('id', 1)->update(['locked_tenant' => $client->locked_tenant]);

        }

        return [
            'success' => true,
            'message' => ($configuration->locked_admin) ? 'Cuenta bloqueada' : 'Cuenta desbloqueada'
        ];

    }

    function lockedTenant(Request $request)
    {
        $response = (new ClientController)->lockedTenant($request);

        return $response;
    }
}
