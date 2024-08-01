<?php

namespace Modules\AddonExample\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Person\Models\Person;

class AddonExampleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $persons = Person::where('type', 'customers')->orderBy('name')->paginate(20);

        return view('addonexample::index', compact('persons'));
    }
}
