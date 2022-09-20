<?php

namespace App\Console;

use Blackshot\CoinMarketSdk\Commands\CoinQuotesCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /* Получит CRIX индекса */
        $schedule->command('blackshot:parse:crix')->daily();

        /* Загрузит новые монеты */
        $schedule->command('blackshot:coin:load')->hourly();

        /* Загрузит список категорий */
        $schedule->command('blackshot:category:load')->daily();

        /* Объединит монеты в категории */
        /* @todo Оптимизировать */
        $schedule->command('blackshot:coin:category')->everyMinute();

        /*
         | 1) Получит котировки по монетам.
         | 2) Обновит сигналы по монетам.
         | 3) Рассчитает коэффициенты alpha, squid, exponential
         |*/
        $schedule->command('blackshot:coin:quotes')
            ->everyThirtyMinutes()
            ->runInBackground();

        //
        $schedule->command('telescope:prune')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
