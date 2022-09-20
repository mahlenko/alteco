<?php

namespace Blackshot\CoinMarketSdk\Providers;

use Blackshot\CoinMarketSdk\Commands\CoinCategoryCommand;
use Blackshot\CoinMarketSdk\Commands\CoinExponentialRankCommand;
use Blackshot\CoinMarketSdk\Commands\CoinRatioCommand;
use Blackshot\CoinMarketSdk\Commands\ParseCrixCommand;
use Blackshot\CoinMarketSdk\Commands\CoinInfoCommand;
use Blackshot\CoinMarketSdk\Commands\CoinLoadCommand;
use Blackshot\CoinMarketSdk\Commands\CoinQuotesCommand;
use Blackshot\CoinMarketSdk\Commands\QuotesGroupDay;
use Blackshot\CoinMarketSdk\Commands\RankGroupCommand;
use Blackshot\CoinMarketSdk\Commands\CategoryTypesCommand;
use Blackshot\CoinMarketSdk\Commands\CoinSignalsCommand;
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
                CoinLoadCommand::class,
                CoinInfoCommand::class,
                CoinQuotesCommand::class,
                CoinSignalsCommand::class,
                CoinRatioCommand::class,
                CoinExponentialRankCommand::class,
                CoinCategoryCommand::class,
                UpdateCategoriesCommand::class,
                RankGroupCommand::class,
                TestMailCommand::class,
                ParseCrixCommand::class,
            ]);
        }

        //
        Paginator::useBootstrap();
    }
}
