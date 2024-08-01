<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.reports.list',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.settings.list_settings',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.reports.list',
            'Modules\BusinessTurn\Http\ViewComposers\BusinessTurnViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\UserViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'Modules\Document\Http\ViewComposers\DocumentViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'Modules\Report\Http\ViewComposers\DownloadTryViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.settings.list_settings',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.settings.list_extras',
            'App\Http\ViewComposers\Tenant\ModuleViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar',
            'Modules\BusinessTurn\Http\ViewComposers\BusinessTurnViewComposer'
        );

        view()->composer(
            'tenant.layouts.app',
            'App\Http\ViewComposers\Tenant\CompactSidebarViewComposer'
        );
        view()->composer(
            'tenant.layouts.app_pos',
            'App\Http\ViewComposers\Tenant\CompactSidebarViewComposer'
        );

        //Ecommerce

        view()->composer(
            'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.featured_products',
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.featured_products_bottom',
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.widget_products',
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            ['tenant.ecommerce.ecommerce.layouts.partials_ecommerce.list_products', 'restaurant::layouts.partials.list_products'],
            'Modules\Ecommerce\Http\ViewComposers\FeaturedProductsViewComposer'
        );
        view()->composer(
            'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.sidemenu',
            'Modules\Ecommerce\Http\ViewComposers\MenuViewComposer'
        );
        view()->composer(
            'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.header_bottom_sticky',
            'Modules\Ecommerce\Http\ViewComposers\MenuViewComposer'
        );
        view()->composer(
            ['tenant.ecommerce.ecommerce.layouts.partials_ecommerce.home_slider'],
            'Modules\Ecommerce\Http\ViewComposers\PromotionsViewComposer'
        );
        view()->composer(
            ['tenant.restaurant.layouts.partials.banner'],
            'Modules\Restaurant\Http\ViewComposers\PromotionsViewComposer'
        );

        view()->composer(
            [
                'tenant.restaurant.layouts.partials.mobile_menu',
                'tenant.restaurant.layouts.partials.header',
                'tenant.restaurant.layouts.partials.footer',
                'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.footer',
                'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.header',
                'tenant.ecommerce.ecommerce.cart.detail',
                'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.sidebar_product_right',
                'tenant.ecommerce.ecommerce.layouts.partials_ecommerce.mobile_menu',
                'tenant.restaurant.cart.detail'
            ],
            'Modules\Ecommerce\Http\ViewComposers\InformationContactViewComposer'
        );
        view()->composer(
            ['tenant.ecommerce.ecommerce.layouts.partials_ecommerce.mobile_menu', 'tenant.restaurant.layouts.partials.mobile_menu'],
            'Modules\Ecommerce\Http\ViewComposers\MenuViewComposer'
        );


        view()->composer(
            'tenant.layouts.partials.sidebar',
            'Modules\LevelAccess\Http\ViewComposers\ModuleLevelViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.header',
            'Modules\LevelAccess\Http\ViewComposers\ModuleLevelViewComposer'
        );

        view()->composer(
            'tenant.layouts.partials.sidebar_styles',
            'App\Http\ViewComposers\Tenant\ConfigurationVisualViewComposer'
        );

        view()->composer(
            'tenant.layouts.app',
            'App\Http\ViewComposers\Tenant\ConfigurationVisualViewComposer'
        );

        view()->composer(
            [
                'tenant.layouts.app',
                'tenant.layouts.auth',
                'tenant.layouts.web'
            ],
            'App\Http\ViewComposers\Tenant\CompanyViewComposer'
        );

       /*view()->composer(
            'ecommerce',
            'Modules\Ecommerce\Http\ViewComposers\TakeProductoViewComposer'
        ); */
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
