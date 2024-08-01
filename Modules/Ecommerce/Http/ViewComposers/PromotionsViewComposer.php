<?php

namespace Modules\Ecommerce\Http\ViewComposers;

use Modules\Item\Models\Promotion;

class PromotionsViewComposer
{
    public function compose($view)
    {
        $view->items = Promotion::where('apply_restaurant', 0)->get();
    }
}
