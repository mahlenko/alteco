<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Platform;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class CoinRepository
{
    /**
     * @param int $id
     * @return Coin|null
     */
    public static function getById(int $id): ?Coin
    {
        return Coin::where('id', $id)->first();
    }

    /**
     * @param int $id
     * @param string $name
     * @param string $symbol
     * @param string $slug
     * @param int $rank
     * @param int $is_active
     * @param DateTimeImmutable|null $first_historical_data
     * @param DateTimeImmutable|null $last_historical_data
     * @param Platform|null $platform
     * @return Coin
     */
    public static function create(
        int $id,
        string $name,
        string $symbol,
        string $slug,
        int $rank,
        int $is_active,
        DateTimeImmutable $first_historical_data = null,
        DateTimeImmutable $last_historical_data = null,
        Platform $platform = null
    ): Coin
    {
        if ($coin = self::getById($id)) {
            return self::update(
                $coin,
                $rank,
                $is_active,
                $first_historical_data,
                $last_historical_data);
        }

        $coin = new Coin();
        $coin->uuid = Uuid::uuid4();
        $coin->fill([
            'id' => $id,
            'name' => $name,
            'symbol' => $symbol,
            'slug' => $slug,
            'rank' => $rank,
            'is_active' => $is_active,
            'first_historical_data' => $first_historical_data->format('Y-m-d H:i:s') ?: null,
            'last_historical_data' => $last_historical_data->format('Y-m-d H:i:s') ?: null,
        ])->save();

        /*  */
        $coin = self::getById($id);
        if ($platform) {
            $coin->attachPlatform($platform);
        }

        return $coin;
    }

    /**
     * @param Coin $coin
     * @param int $rank
     * @param int $is_active
     * @param DateTimeImmutable|null $first_historical_data
     * @param DateTimeImmutable|null $last_historical_data
     * @return Coin
     */
    public static function update(
        Coin $coin,
        int $rank,
        int $is_active,
        DateTimeImmutable $first_historical_data = null,
        DateTimeImmutable $last_historical_data = null
    ): Coin
    {
        $coin->fill([
            'rank' => $rank,
            'is_active' => $is_active,
            'first_historical_data' => $first_historical_data,
            'last_historical_data' => $last_historical_data
        ])->save();

        return $coin;
    }

    /**
     * @param Collection $quotes
     * @param string $key
     * @param string $date_format
     * @return \Illuminate\Support\Collection
     */
    public static function groupByDate(
        Collection $quotes,
        string $key = 'last_updated',
        string $date_format = 'Y-m-d'
    ): \Illuminate\Support\Collection
    {
        return $quotes->mapToGroups(function ($item) use ($key, $date_format) {
            $date = (new DateTimeImmutable($item->$key))->format($date_format);
            return [$date => $item];
        });
    }

    /**
     * @param string $start_period
     * @param string $end_period
     * @return \Illuminate\Database\Query\Builder
     */
    public static function signalsPeriodBuilder(
        string $start_period,
        string $end_period
    ): \Illuminate\Database\Query\Builder
    {
        return DB::table('signals')
            ->distinct()
            ->select('signals.coin_uuid')
            ->selectRaw('FIRST_VALUE(`rank`) OVER (PARTITION BY coin_uuid ORDER BY date ASC) previous_rank')
            ->selectRaw('FIRST_VALUE(`rank`) OVER (PARTITION BY coin_uuid ORDER BY date DESC) current_rank')
            ->whereBetween('date', [$start_period, $end_period]);
    }

    /**
     * @param Collection $quotes
     * @return Collection
     */
    public static function calculateRankChanged(Collection $quotes): Collection
    {
        $quotes->map(function ($quote, $index) use ($quotes) {
            if (!$index) {
                $quote->cmc_rank_changed = 0;
                return;
            }

            $last_quote = $quotes->get($index - 1);
            $quote->cmc_rank_changed = $last_quote->cmc_rank - $quote->cmc_rank;
        });

        return $quotes;
    }

    /**
     * @param DateTimeImmutable $begin
     * @param DateTimeImmutable $end
     * @param string $direction
     * @param string|null $query
     * @param array $categories
     * @return Builder
     */
    public static function sortByRankChangePeriod(
        DateTimeImmutable $begin,
        DateTimeImmutable $end,
        string $direction,
        string $query = null,
        array $categories = []
    ) : Builder
    {
        $select_raw = '(' . implode(' - ', [
                'FIRST_VALUE(cmc_rank) OVER (PARTITION BY coin_uuid ORDER BY last_updated DESC)', // последнее значение
                'FIRST_VALUE(cmc_rank) OVER (PARTITION BY coin_uuid ORDER BY last_updated ASC)' // первое значение
            ]) .')';

        $begin = $begin->setTime(0, 0, 0);
        $end = $end->setTime(23, 59, 59);

        $coins = DB::table('coin_quotes')
            ->distinct()
            ->select('coin_uuid')
            ->selectRaw('('. $select_raw . ') as rank_change')
            ->orderBy('rank_change', $direction)
            ->whereBetween('last_updated', [
                $begin->format('Y-m-d 00:00:00'),
                $end->format('Y-m-d 23:59:59')
            ]);

        $coin_list_uuid = $coins->pluck('coin_uuid');
        $coin_list_uuid_str = '"' . $coin_list_uuid->join('","') . '"';

        $coins_result = Coin::with(['info', 'quotes'])
            ->select('coins.*')
            ->orderBy(DB::raw("FIELD (uuid, ". $coin_list_uuid_str .") "));

        return self::filterCategories(
            $categories,
            $query,
            self::filterQuery($coins_result, $query)
        );
    }

    /**
     * @param Builder $builder
     * @param string|null $query
     * @return Builder
     */
    public static function filterQuery(Builder $builder, string $query = null): Builder
    {
        if (!empty($query)) {
            return $builder->where(function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
                $q->orWhere('symbol', $query);
            });
        }

        return $builder;
    }

    /**
     * @param array $categories_uuid
     * @param string|null $query
     * @param Builder $builder
     * @return Builder
     */
    public static function filterCategories(
        array $categories_uuid,
        string $query,
        Builder $builder
    ): Builder
    {
        if ($categories_uuid) {
            $builder->join(
                'coin_categories',
                'coins.uuid',
                'coin_categories.coin_uuid'
            );

            $builder->whereIn('coin_categories.category_uuid', $categories_uuid);

            if (in_array('favorites', $categories_uuid)) {
                //
                $favorites = Auth::user()->favorites;
                if ($query && $favorites) {
                    $favorites = $favorites->filter(function($favorite) use ($query) {
                        $query = Str::lower($query);

                        return Str::contains(Str::lower($favorite->name), $query)
                            || $query === Str::lower($favorite->symbol);
                    });
                }


                if ($favorites->count()) {
                    $builder->orWhereIn('coins.uuid', $favorites->pluck('uuid'));
                    $builder->groupBy('coins.uuid');
                }
            }

        }

        return $builder;
    }
}
