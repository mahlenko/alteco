<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class RankChangeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:rank:change {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Сбор изменения ранжирования за 30, 60 дней';

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
        $date = new DateTimeImmutable('-'. $this->argument('days') .' days');
        $date_last = Signal::max('date');

        $signals = Signal::where('date', '=', $date->format('Y-m-d'))
            ->orWhere('date', '=', $date_last)
            ->get();

        $signals_group = $signals->groupBy('coin_uuid');

        $signals_group->map(function ($signal) {
            /* @var Collection $signal */
            if ($signal->count() == 2) {

                $signal->sortBy('date');
                $diff_rank = $signal[0]->rank - $signal[1]->rank;

                $key_rank = 'rank_'. $this->argument('days') .'d';

                $coin = Coin::find($signal[0]->coin_uuid);
                $coin->$key_rank = $diff_rank;
                $coin->save();

                $this->info('UPDATE ' . $this->argument('days') .'d '. $coin->name);

            }
        });

        $this->info('DONE');
        return Command::SUCCESS;
    }
}
