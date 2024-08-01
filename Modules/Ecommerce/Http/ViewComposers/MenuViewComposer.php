<?php

namespace Modules\Ecommerce\Http\ViewComposers;

use Modules\Item\Models\Tag;

class MenuViewComposer
{
    public function compose($view)
    {
        $view->items = Tag::all();
    }
}
