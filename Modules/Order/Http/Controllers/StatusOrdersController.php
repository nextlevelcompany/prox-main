<?php

namespace Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Order\Models\StatusOrder;

class StatusOrdersController extends Controller
{
    public function records()
    {
        return response()->json(StatusOrder::all());
    }
}
