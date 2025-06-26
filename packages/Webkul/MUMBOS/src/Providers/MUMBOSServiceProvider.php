<?php

namespace Webkul\MUMBOS\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Webkul\MUMBOS\Models\Shareholder;
use Webkul\MUMBOS\Models\Contribution;
use Webkul\MUMBOS\Services\Payments\Contracts\PaymentGateway;
use Webkul\MUMBOS\Services\Payments\BankTransferGateway;
use Webkul\MUMBOS\Services\Payments\MpesaGateway;
use Webkul\MUMBOS\Services\Payments\PayPalGateway;

class MUMBOSServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {


        \Webkul\Customer\Models\Customer::resolveRelationUsing('shareholder', function ($customer) {
            return $customer->hasOne(
                Shareholder::class,
                'customer_id'
            );
        });
    
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/shop-routes.php');
          $this->loadRoutesFrom(__DIR__ . '/../Routes/api-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'mumbos');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'mumbos');

        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('mumbos::admin.layouts.style');
        });


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

          $this->app->bind(
            PaymentGateway::class,
            function ($app) {
                // Default fallback
                return $app->make(BankTransferGateway::class);
            }
        );
         $this->app->singleton(\Webkul\MUMBOS\Services\Payments\PaymentGatewayFactory::class, function($app) {
            return new \Webkul\MUMBOS\Services\Payments\PaymentGatewayFactory($app);
        });

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}