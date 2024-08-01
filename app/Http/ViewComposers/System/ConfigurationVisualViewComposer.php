<?php

namespace App\Http\ViewComposers\System;

use Modules\System\Models\Configuration;

class ConfigurationVisualViewComposer
{
    public function compose($view)
    {
        $configuration = Configuration::first();
        $view->system_visual = $configuration->visual;
    }
}
