<?php

namespace App\Console;

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
        /*
         | Спарсит CRIX индекс
        */
        $schedule->command('blackshot:parse:crix')
            ->daily()
            ->runInBackground();

        /*
         | Обновит список категорий
        */
        $schedule
            ->command('blackshot:category:load')
            ->daily();

        /*
         | Проверяет обновления только для категорий
         | у которых last_updated >= 1 дня.
         | last_updated обновляется вместе с командой blackshot:category:load
        */
        $schedule->command('blackshot:coin:category', ['days' => 1])->daily();

        /*
         | Загрузит новые монеты и сразу выполнит получение 'blackshot:coin:quotes'
        */
        $schedule->command('blackshot:coin:load')->daily();

        /*
         | 1) Получит котировки по монетам.
         | 2) Обновит сигналы по монетам.
         | 3) Рассчитает коэффициенты alpha, squid, exponential
         |*/
        $schedule->command('blackshot:coin:quotes')
            ->hourly()
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
