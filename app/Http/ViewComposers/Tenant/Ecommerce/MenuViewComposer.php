<?php

namespace App\Http\ViewComposers\Tenant\Ecommerce;

use Modules\Catalog\Models\Tag;

class MenuViewComposer
{
    public function compose($view)
    {
        $view->items = Tag::all();
    }
}
