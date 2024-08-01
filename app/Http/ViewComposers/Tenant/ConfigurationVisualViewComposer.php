<?php

namespace App\Http\ViewComposers\Tenant;

use Modules\Company\Http\Resources\ConfigurationResource;
use Modules\Company\Models\Configuration;

class ConfigurationVisualViewComposer
{
    public function compose($view)
    {
        $configuration = Configuration::query()->first();
        if(is_null($configuration->visual)) {
            $defaults = [
                'bg' => 'light',
                'header' => 'light',
                'sidebars' => 'light',
            ];
            $configuration->visual = $defaults;
            $configuration->save();
        }
        $configuration = Configuration::query()->first();
        $record = new ConfigurationResource($configuration);
        $view->visual = $record->visual;
    }
}
