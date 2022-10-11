<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Exception;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CoinSignalsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:coin:signals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Соберет сигналы за дату (по-умолчанию: текущая дата)';

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
    public function handle(): int
    {
        $this->info($this->description);

        $last_updated = DB::table('coin_quotes')->max('last_updated');
        $date = new DateTimeImmutable($last_updated);

        $dateFormat = $date->format('Y-m-d');

        $quotes = self::signals($date);

        /* Подготовка данных */
        $upsert = $quotes->map(function($quote) use ($dateFormat) {
            /* Сбросим кеш сигналов */
            if (Cache::has('signals:' . $quote->coin_uuid)) {
                Cache::forget('signals:'. $quote->coin_uuid);
                $this->info('Сброшен кеш для UUID: ' . $quote->coin_uuid);
            }

            return [
                'coin_uuid' => $quote->coin_uuid,
                'rank' => $quote->last_rank,
                'diff' => $quote->first_rank - $quote->last_rank,
                'date' => $dateFormat
            ];
        });

        /* Разделим запросы по 500 записей, на случай их большого количества */
        $upsert->chunk(500)->each(function($chunk) {
            Signal::upsert($chunk->toArray(), ['coin_uuid', 'date'], ['rank', 'diff']);
        });

        /* Покажем собранные данные */
        $this->table(['UUID', 'Rank', 'Diff', 'Date'], $upsert->toArray());

        return self::SUCCESS;
    }

    /**
     * Вернет данные за период
     *
     * first_rank - первая запись с ранком за период
     * last_rank - последняя запись с ранком
     *
     * @param DateTimeImmutable $date
     * @return Collection
     */
    private function signals(DateTimeImmutable $date): Collection
    {
        return DB::table('coin_quotes')
            ->distinct()
            ->select('coin_uuid')
            ->selectRaw('FIRST_VALUE(`cmc_rank`) OVER (PARTITION BY coin_uuid ORDER BY last_updated ASC) first_rank')
            ->selectRaw('FIRST_VALUE(`cmc_rank`) OVER (PARTITION BY coin_uuid ORDER BY last_updated DESC) last_rank')
            ->whereBetween('last_updated', [
                $date->setTime(0,0),
                $date->setTime(23,59,59)
            ])->get();
    }

}
