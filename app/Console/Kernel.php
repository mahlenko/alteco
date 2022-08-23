<?php

namespace App\Console;

use Blackshot\CoinMarketSdk\Commands\QuotesFollowingCommand;
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
        /* загрузка монет и их рейтинга */
        $schedule->command('blackshot:coin:map')->hourly();

        /* обновит информацию по монетам (логотип, описание, ссылки) */
        $schedule->command('blackshot:coin:info')->daily()->runInBackground();

        /* получит цену на монеты, проценты на сколько изменилась монета, также рейтинг как и в map */
        $schedule->command('blackshot:coin:quotes')->everySixHours()->runInBackground();

        /* цены на избранные монеты - тоже что и предыдущая функция, только для избранных и чаще */
        $schedule->command('blackshot:coin:quotes', ['--favorite'])->hourly();

        /* Категории для монет */
        $schedule->command('blackshot:category')->everyMinute();

        /* Соберет сигналы - временно, чтобы загрузить старые сигналы */
//        $schedule->command('blackshot:signals')->dailyAt('02:00');

        /* Рассчитать rank 30 дней */
        $schedule->command('blackshot:rank:change 30')
            ->hourly()
            ->runInBackground();

        /* Рассчитать rank 60 дней */
        $schedule->command('blackshot:rank:change 60')
            ->hourly()
            ->runInBackground();

        /* Получение CRIX индекса */
        $schedule->command('blackshot:crix:indices')->daily();

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
