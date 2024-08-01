<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\FormatTemplate;

class FormatTemplateController extends Controller
{
    public function records() {

        $formats = FormatTemplate::all();

        return new FormatTemplateCollection($formats);
    }
}
