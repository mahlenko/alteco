<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SignalRepository
{
    /**
     * @param object $filter
     * @param object $sortable
     * @param Collection|null $except_uuid
     * @return Collection
     * @throws Exception
     */
    public static function coinCollection(
        object $filter,
        object $sortable,
        Collection $except_uuid = null
    ): Collection
    {
        // cache hash
        $hash = md5(http_build_query(array_merge((array)$filter, (array)$sortable, $except_uuid->toArray())));

        return Cache::remember($hash, time() + 600, function() use ($filter, $sortable, $except_uuid) {
            $builder = self::queryBuilder();

            $end_date = new DateTimeImmutable(Signal::max('date'));
            $start_date = $end_date->modify('-'. $filter->days .' days');

            $builder->whereBetween('date', [
                $start_date->format('Y-m-d'),
                $end_date->format('Y-m-d')
            ]);

            if ($filter->categories_uuid) {
                $builder->join('coin_categories', 'coin_categories.coin_uuid', '=', 'signals.coin_uuid');
                $builder->whereIn('coin_categories.category_uuid', $filter->categories_uuid);

                if (in_array('favorites', $filter->categories_uuid)) {
                    $builder->leftJoin('user_favorites', 'user_favorites.coin_uuid', '=', 'signals.coin_uuid');
                    $builder->orWhere('user_favorites.user_id', Auth::id());
                }
            }

            if ($except_uuid) {
                $builder->whereNotIn('signals.coin_uuid', $except_uuid);
            }

            $signals = $builder->get();
    //        $signals = self::rankChangeCalculate($signals, $filter->signals);
            $signals = self::rankChangeCalculate($signals, 0);
            if (!$signals->count()) return $signals;

            $changed_to = self::maxChangedRankByDay($signals, $end_date);
            $signals_uuid = self::signalsChangedByRankByDate($changed_to, $end_date);

            self::appendSignals($signals, $signals_uuid->toArray(), $signals->max('diff'), $filter->min_rank);

            $signals = self::filterBySignals($signals, $filter->signals);

            return self::sortBy(
                $signals,
                $sortable->column,
                $sortable->direction
            );
        });
    }

    /**
     * @param object $filter
     * @param Collection $uuids
     * @return Collection|null
     * @throws Exception
     */
    public static function buyingCollection(object $filter, Collection $uuids): ?Collection
    {
        if (!$uuids->count()) return null;

        $builder = self::queryBuilder();

        $end_date = new DateTimeImmutable(Signal::max('date'));
        $start_date = $end_date->modify('-'. $filter->days .' days');

        $builder->whereBetween('date', [
            $start_date->format('Y-m-d'),
            $end_date->format('Y-m-d')
        ]);

        $builder->whereIn('signals.coin_uuid', $uuids);

        $signals = $builder->get();

        return self::rankChangeCalculate($signals);
    }

    /**
     * Добавит информацию о ранке сигналов
     * @param Collection $collection
     * @param int|null $min_change_rank
     * @return Collection
     */
    public static function rankChangeCalculate(Collection $collection, int $min_change_rank = null): Collection
    {
        $collection->map(function($item) {
            $item->diff = $item->previous_rank - $item->current_rank;
        });

        return is_null($min_change_rank)
            ? $collection
            : $collection->where('diff', '>', $min_change_rank)->values();
    }

    /**
     * @param Collection $signals
     * @param DateTimeImmutable $date
     * @return int
     */
    public static function maxChangedRankByDay(Collection $signals, DateTimeImmutable $date): int
    {
        return Signal::where('signals.date', $date->format('Y-m-d'))
            ->whereIn('signals.coin_uuid', $signals->pluck('coin_uuid'))
            ->max('diff');
    }

    /**
     * Сигналы за выбранную дату которые изменились на $changed
     * @param int $changed_to
     * @param DateTimeImmutable $date
     * @return Collection
     */
    public static function signalsChangedByRankByDate(int $changed_to, DateTimeImmutable $date): Collection
    {
        return Signal::where('date', $date->format('Y-m-d'))
            ->where('diff', $changed_to)
            ->pluck('coin_uuid');
    }

    /**
     * Добавит в монеты сигналы на которые они сработали
     * @param Collection $signals
     * @param array $max_changed_day_signals
     * @param int $max_changed_period
     * @param int $changed_more
     * @return void
     */
    public static function appendSignals(
        Collection &$signals,
        array $max_changed_day_signals,
        int $max_changed_period,
        int $changed_more
    )
    {
        $signals->map(function($signal) use ($max_changed_day_signals, $max_changed_period, $changed_more) {
            $signal->signal_max_diff = in_array($signal->coin_uuid, $max_changed_day_signals);
            $signal->signal_max_period = $signal->diff == $max_changed_period;
            $signal->signal_more_change_rank = $signal->diff >= $changed_more;
        });
    }

    /**
     * @param Collection $coins
     * @param array $signals
     * @return Collection
     */
    public static function filterBySignals(Collection $coins, array $signals = []): Collection
    {
        if ($signals) {
            return $coins->filter(function($coin) use ($signals) {
                foreach ($signals as $signal) {
                    if (!isset($coin->$signal)) continue;
                    if ($coin->$signal) {
                        return true;
                    }
                }

                return false;
            });
        }

        return $coins;
    }

    /**
     * @param Collection $collection
     * @param string $column
     * @param string $direction
     * @return Collection
     */
    public static function sortBy(Collection &$collection, string $column, string $direction = 'asc')
    {
        return $direction === 'asc'
            ? $collection->sortBy($column)->values()
            : $collection->sortByDesc($column)->values();
    }

    /**
     * @param Collection $collection
     * @param int $page
     * @param int $per_page
     * @return Collection
     */
    public static function pagination(Collection $collection, int $page = 1, int $per_page = 25): Collection
    {
        return $collection->forPage($page, $per_page);
    }

    /**
     * @return Builder
     * @throws Exception
     */
    public static function queryBuilder(): Builder
    {
        return DB::table('signals')
            ->distinct()
            ->select('signals.coin_uuid')
            ->selectRaw('FIRST_VALUE(`rank`) OVER (PARTITION BY coin_uuid ORDER BY date ASC) previous_rank')
            ->selectRaw('FIRST_VALUE(`rank`) OVER (PARTITION BY coin_uuid ORDER BY date DESC) current_rank');
    }

}
