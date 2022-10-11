<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\Coin;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class QuoteRepository
{
    /**
     * @param Coin|null $coin
     * @return Collection
     */
    public static function price(Coin $coin = null): Collection
    {
        $cacheKey = $coin ? 'price:'. $coin->uuid : 'price:all';

        return Cache::remember($cacheKey, time() + (60 * 30), function() use ($coin) {
            $builder = DB::table('coin_quotes');
            if ($coin) $builder->where('coin_uuid', $coin->uuid);

            return $builder
                ->select(['coin_uuid', 'price', 'last_updated'])
                ->orderBy('last_updated')
                ->get()
                ->mapToGroups(function($item) {
                    return [
                        $item->coin_uuid => [
                            'price' => $item->price,
                            'last_updated' => $item->last_updated
                        ]
                    ];
                });
        });
    }

    /**
     * Доходность рынка
     * @param Collection $priceCollection
     * @return Collection
     * @throws Exception
     */
    public static function crix(Collection $priceCollection): Collection
    {
        $dates = $priceCollection->pluck('last_updated');

        $dateBetween = [
            (new DateTimeImmutable($dates->min()))->format('Y-m-d'),
            (new DateTimeImmutable($dates->max()))->format('Y-m-d')
        ];

        return DB::table('crix_indices')
            ->select('index', 'date')
            ->whereBetween('date', $dateBetween)
            ->orderBy('date')
            ->get();
    }
}
