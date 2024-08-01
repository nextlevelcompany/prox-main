<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Hyn\Tenancy\Environment;
use Illuminate\Http\Request;
use Modules\Account\Http\Controllers\AccountController;
use Modules\System\Http\Resources\ClientCollection;
use Modules\System\Models\Client;

class AccountingController extends Controller
{
    public function index()
    {
        return view('system.accounting.index');
    }

    public function records()
    {
        $records = Client::latest()->get();
        return new ClientCollection($records);
    }

    public function download(Request $request)
    {
        $client = Client::findOrFail($request->id);
        $tenancy = app(Environment::class);
        $tenancy->tenant($client->hostname->website);

        return app(AccountController::class)->download($request);
    }
}
