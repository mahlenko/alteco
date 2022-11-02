<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Quote;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CoinPriceStatisticAction
{
    /**
     * @param $coins
     * @param DateTimeImmutable $start
     * @param DateTimeImmutable|null $end
     * @return Collection
     */
    public static function handle(
        $coins,
        DateTimeImmutable $start,
        DateTimeImmutable $end = null): Collection
    {
        if (!$end) {
            $end = new DateTimeImmutable();
        }

        if ($coins instanceof Coin) $coins = [ $coins->toArray() ];
        if ($coins instanceof Collection) $coins = $coins->toArray();

        // Получим данные за каждый час
        $quotes = DB::query()
            ->from((new Quote)->getTable())
            ->select('coin_uuid')
            ->selectRaw('DATE(last_updated) AS DATE')
            ->selectRaw('HOUR(last_updated) AS HOUR')
            ->selectRaw('AVG(price) AS price')
            ->whereIn('coin_uuid', array_column($coins, 'uuid'))
            ->whereBetween('last_updated', [$start, $end])
            ->groupByRaw('coin_uuid, DATE, HOUR')
            ->orderByRaw('coin_uuid, DATE, HOUR')
            ->get();

        // Создадим колонку с датой
        $quotes->map(function($item) {
            $item->last_updated = (new DateTimeImmutable($item->DATE))
                ->setTime($item->HOUR, 0)
                ->format('Y-m-d H:i:s');
        });

        return $quotes->groupBy('coin_uuid');
    }
}
