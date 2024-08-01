<?php
namespace Modules\System\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SystemVisualServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'system.layouts.app',
            'App\Http\ViewComposers\System\ConfigurationVisualViewComposer'
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
