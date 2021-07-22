<?php

namespace ToanHung94mt3\PaymentPackage;

use Illuminate\Support\ServiceProvider;
use ToanHung94mt3\PaymentPackage\Console\InstallPaymentPackage;

class PaymentProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'paymentpackage');
    }

    public function boot()
    {
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallPaymentPackage::class,
            ]);

            $this->publishes([
                __DIR__.'/config/config.php' => config_path('paymentpackage.php'),
              ], 'config');
        }
    }
}
