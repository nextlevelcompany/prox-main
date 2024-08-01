<?php

namespace Modules\Ecommerce\Http\ViewComposers;

use Illuminate\View\View;
use Modules\Item\Models\Item;

class TakeProductoViewComposer
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $records = Item::orderBy('id', 'DESC')->take(2)->get();

         $view->with('records', $records);
    }
}
