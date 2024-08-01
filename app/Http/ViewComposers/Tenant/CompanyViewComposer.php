<?php

namespace App\Http\ViewComposers\Tenant;

use Modules\Company\Models\Company;
use Modules\Order\Models\Order;

class CompanyViewComposer
{
    public function compose($view)
    {
        $view->vc_company = Company::query()->first();
        $view->vc_orders = Order::query()
            ->where('status_order_id', 1)
            ->count();
    }
}
