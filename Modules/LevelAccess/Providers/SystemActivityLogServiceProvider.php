<?php

namespace Modules\LevelAccess\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Company\Models\Company;
use Modules\LevelAccess\Traits\SystemActivityTrait;


class SystemActivityLogServiceProvider extends ServiceProvider
{

    use SystemActivityTrait;

    public function boot()
    {
        $this->checkCompany();
    }

    public function register()
    {
    }


    /**
     *
     * Verificar cambios en configuracion de empresa
     *
     * @return void
     */
    private function checkCompany()
    {
        Company::updated(function ($company) {
            $this->checkModelChanges($company);
        });
    }

}
