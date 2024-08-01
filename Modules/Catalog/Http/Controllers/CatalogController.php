<?php

namespace Modules\Catalog\Http\Controllers;

use Illuminate\Routing\Controller;

class CatalogController extends Controller
{
    public function index()
    {
        return view('tenant.catalogs.index');
    }
}
