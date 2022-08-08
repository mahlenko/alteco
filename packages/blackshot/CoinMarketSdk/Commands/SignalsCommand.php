<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\Quote;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class SignalsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:signals {--date=now}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сбор сигналов';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $date = new DateTimeImmutable($this->option('date'));

        $date_string = $date->format('Y-m-d');

        $signals_latest = Signal::where('date', $date->modify('-1 day')->format('Y-m-d'))->get();
        $signals_day = Signal::where('date', $date_string)->get();

        $this->warn('BUILDING DATA '. $date->format('d.m.Y'));

        $quotes = DB::table('coin_quotes')
            ->distinct()
            ->select('coin_uuid')
//            ->selectRaw('FIRST_VALUE(`cmc_rank`) OVER (PARTITION BY coin_uuid ORDER BY last_updated ASC) start_day_rank')
            ->selectRaw('FIRST_VALUE(`cmc_rank`) OVER (PARTITION BY coin_uuid ORDER BY last_updated DESC) end_day_rank')
            ->whereBetween('last_updated', [
                $date->format('Y-m-d 00:00:00'),
                $date->format('Y-m-d 23:59:59')
            ])->get();

        $this->warn('DATA COMPLETE');

        foreach ($quotes as $quote) {
            //
            if (Cache::has('signals:'. $quote->coin_uuid)) {
                Cache::forget('signals:'. $quote->coin_uuid);
            }

            //
            try {
                $signal = $signals_day
                    ->where('coin_uuid', $quote->coin_uuid)
                    ->first();

                $latest = $signals_latest
                    ->where('coin_uuid', $quote->coin_uuid)
                    ->first();

                if (!$signal) {
                    $signal = new Signal();
                    $signal->coin_uuid = $quote->coin_uuid;
                    $signal->date = $date_string;
                }

                $signal->rank = $quote->end_day_rank;
                $signal->diff = $latest
                    ? $latest->rank - $quote->end_day_rank
                    : 0;

                $signal->save();

            } catch (Exception $exception) {
                $this->warn($quote->coin_uuid .': '. $exception->getMessage());
            }
        }

        // Расчет экспоненциального ранка
        $this->call('blackshot:rank:exponential');

        $this->info('DONE');
        return Command::SUCCESS;
    }
}
