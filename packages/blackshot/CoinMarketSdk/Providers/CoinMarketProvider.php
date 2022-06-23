<?php

namespace Blackshot\CoinMarketSdk\Providers;

use Blackshot\CoinMarketSdk\Commands\CategoryCommand;
use Blackshot\CoinMarketSdk\Commands\InfoCommand;
use Blackshot\CoinMarketSdk\Commands\MapCommand;
use Blackshot\CoinMarketSdk\Commands\QuotesFollowingCommand;
use Blackshot\CoinMarketSdk\Commands\QuotesGroupDay;
use Blackshot\CoinMarketSdk\Commands\RankChangeCommand;
use Blackshot\CoinMarketSdk\Commands\SetCategoryTypesCommand;
use Blackshot\CoinMarketSdk\Commands\SignalsCommand;
use Blackshot\CoinMarketSdk\Commands\TestMailCommand;
use Blackshot\CoinMarketSdk\Commands\UpdateCategoriesCommand;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class CoinMarketProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // ...
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__.'/../Configs/coinmarket.php' => config_path('coinmarket.php'),
        ]);

        //
        $this->mergeConfigFrom(__DIR__.'/../Configs/coinmarket.php', 'coinmarket');

        //
        $this->loadMigrationsFrom(realpath(__DIR__ . '/../Migrations'));

        //
        $this->loadRoutesFrom(__DIR__ . '/../Configs/routes.php');
        $this->loadRoutesFrom(__DIR__ . '/../Configs/breadcrumbs.php');

        //
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'blackshot');

        //
        if ($this->app->runningInConsole()) {
            $this->commands([
                UpdateCategoriesCommand::class,
                CategoryCommand::class,
                SetCategoryTypesCommand::class,
                InfoCommand::class,
                MapCommand::class,
                QuotesFollowingCommand::class,
                QuotesGroupDay::class,
                SignalsCommand::class,
                RankChangeCommand::class,
                TestMailCommand::class,
            ]);
        }

        //
        Paginator::useBootstrap();
    }
}
