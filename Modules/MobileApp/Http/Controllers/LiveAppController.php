<?php

namespace Modules\MobileApp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class LiveAppController extends Controller
{
    public function index()
    {
        return view('tenant.mobile_app.mobile_app.index');
    }

    public function configuration()
    {
        return view('tenant.mobile_app.configuration.index');
    }

    public function premium()
    {
        return view('tenant.mobile_app.mobile_app_white.index');
    }
}
