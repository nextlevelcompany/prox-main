<?php

namespace Modules\Company\Http\Controllers;

use App\Http\Controllers\Controller;

class AdvancedController extends Controller
{
    public function index() {
        return view('tenant.advanced.index');
    }
}
