<?php

namespace App\Http\ViewComposers\Tenant\Ecommerce;

use Modules\Item\Models\Promotion;

class PromotionsViewComposer
{
    public function compose($view)
    {
        $view->items = Promotion::all();
    }
}
