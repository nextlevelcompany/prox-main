<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Models\GlobalPayment;
use Modules\Cash\Models\Cash;

class FinanceController extends Controller
{

    public function records(Request $request)
    {
        $records = GlobalPayment::whereDestinationType(Cash::class)->first();

        return $records->destination->cash_documents;
    }
}
