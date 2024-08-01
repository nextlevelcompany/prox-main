<?php

namespace App\Http\ViewComposers\Tenant;

use Modules\System\Models\Configuration;
use \Modules\Company\Models\Configuration as TenantConfiguration;
use Modules\LevelAccess\Models\Module;

class ModuleViewComposer
{
    public function compose($view)
    {
        $modules = auth()->user()->modules()->pluck('value')->toArray();
        /*
        $systemConfig = Configuration::select('use_login_global')->first();
        */
        $systemConfig = Configuration::getDataModuleViewComposer();

        if(count($modules) > 0) {
            $view->vc_modules = $modules;
        } else {
            $view->vc_modules = Module::all()->pluck('value')->toArray();
        }
        $view->vc_configuration = TenantConfiguration::query()->first();

        $view->useLoginGlobal = $systemConfig->use_login_global;

        $view->tenant_show_ads = $systemConfig->tenant_show_ads;
        $view->url_tenant_image_ads = $systemConfig->getUrlTenantImageAds();

    }
}
