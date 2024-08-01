<?php

namespace Modules\Ecommerce\Http\ViewComposers;

use Modules\Ecommerce\Models\ConfigurationEcommerce;

class InformationContactViewComposer
{
    public function compose($view)
    {
        $view->information = ConfigurationEcommerce::first();
    }
}
