<?php

namespace Blackshot\CoinMarketSdk\Portfolio;

use Illuminate\Support\ServiceProvider;

class PortfolioProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->mergeConfigFrom(__DIR__.'/config.php', 'portfolio');
    }
}
