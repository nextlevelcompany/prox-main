<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Inventory\Models\Guide;
use Modules\Inventory\Models\InventoryTransfer;
use Modules\Inventory\Observers\GuideObserver;
use Modules\Inventory\Observers\InventoryTransferObserver;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Inventory';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'inventory';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerViews();
        Guide::observe(GuideObserver::class);
        InventoryTransfer::observe(InventoryTransferObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(InventoryChangeServiceProvider::class);
        $this->app->register(InventoryKardexServiceProvider::class);
        $this->app->register(InventoryVoidedServiceProvider::class);
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
