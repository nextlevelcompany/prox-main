<?php
namespace Modules\Report\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Modules\System\Models\Client;
use Modules\System\Http\Resources\ClientCollection;


class ReportController extends Controller
{

    public function listReports()
    {
        return view('report::system.list_reports');
    }

    public function clients()
    {
        $records = Client::latest()->get(); 
        return new ClientCollection($records);
    }

}
