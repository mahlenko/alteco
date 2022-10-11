<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use App\Models\User;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Сигналы - изменения позиций монет за определенный промежуток времени.
 */
class SignalRepository
{
    public static function handle(Coin $coin = null, array $period = []): Collection
    {
        $cacheKey = $coin ? 'signals:'. $coin->uuid : 'signals:all';

        if ($period) {
            $prefix = $coin ? $cacheKey .':' : 'signals:';
            $cacheKey = $prefix . join(',', [
                $period[0]->format('Y-m-d'),
                $period[1]->format('Y-m-d')
            ]);
        }

        return Cache::remember($cacheKey, time() + (60 * 30), function() use ($coin, $period) {
            $builder = DB::table('signals')
                ->select(['coin_uuid', 'rank', 'date'])
                ->orderBy('date');

            if ($coin) $builder->where('coin_uuid', $coin->uuid);
            if ($period) $builder->whereBetween('date', $period);

            return $builder->get()->mapToGroups(function($signal) {
                return [$signal->coin_uuid => [
                    'rank' => $signal->rank,
                    'date' => $signal->date
                ]];
            });
        });
    }

    /**
     * Вернет изменение монет за промежуток времени
     * @param int $days
     * @return Collection
     */
    public static function inDays(int $days): Collection
    {
        $today = new DateTimeImmutable();

        // Начальная дата
        $start_period = $today->modify('-'. $days .' days');

        // Используем кеширование, до начала следующего часа.
        $cache_ttl = time() + (60 - $today->format('i')) * 60;

        return Cache::remember('signals:' . $days, $cache_ttl, function() use ($start_period, $today) {
            return self::queryBuilder()
                ->whereBetween('date', [
                    $start_period->format('Y-m-d'),
                    $today->format('Y-m-d'),
                ])->get()->each(function ($item) {
                    $item->diff = $item->previous_rank - $item->current_rank;
                });
        });
    }

    /**
     * Фильтр сигналов по категориям
     * @param Collection $signals
     * @param array $categories
     * @param User $user
     * @return Collection
     */
    public static function filterByCategory(Collection $signals, array $categories, User $user): Collection
    {
        $favoriteTokens = [];

        // Получим избранные монеты, если выбран поиск по избранному
        if (in_array('favorites', $categories)) {
            $favoriteTokens = $user->favorites->pluck('uuid')->toArray();
            unset($categories[array_search('favorites', $categories)]);
        }

        // Фильтруем монету по категориям и избранному пользователя
        if ($favoriteTokens || $categories) {
            $signals = $signals->filter(function ($signal) use ($favoriteTokens, $categories) {
                $isFavorite = in_array($signal->coin_uuid, $favoriteTokens);
                $isHasCategory = in_array($signal->category_uuid, $categories);

                return $isFavorite || $isHasCategory;
            });
        }

        /*
         | Если монета находится в нескольких категориях она будет присутствовать
         | несколько раз в коллекции в каждой категории. Нам же, после фильтрации
         | важно вернуть только монету, поэтому возвращаем уникальные `coin_uuid`.
        */
        return $signals->unique('coin_uuid');
    }

    /**
     * @param object $filter
     * @param object $sortable
     * @param Collection|null $except_uuid
     * @return Collection
     * @throws Exception
     */
//    public static function coinCollection(
//        object $filter,
//        object $sortable,
//        Collection $except_uuid = null
//    ): Collection
//    {
//        // cache hash
//        $hash_filter = (array) $filter;
//        $hash_filter['user_id'] = Auth::id();
//        if (key_exists('categories_uuid', $hash_filter) && in_array('favorites', $hash_filter['categories_uuid'])) {
//            $index = array_search('favorites', $filter->categories_uuid);
//            $hash_filter['categories_uuid'][$index] = Auth::user()->id;
//        }
//
//        $hash = md5(http_build_query(array_merge($hash_filter, (array)$sortable, $except_uuid->toArray())));
//
//        $cache_ttl = time() + 300; // cache ttl: 20 minutes
//        return Cache::remember($hash, $cache_ttl, function() use ($filter, $sortable, $except_uuid) {
//            $builder = self::queryBuilder();
//
//            $end_date = new DateTimeImmutable(Signal::max('date'));
//            $start_date = $end_date->modify('-'. $filter->days .' days');
//
//            $builder->whereBetween('date', [
//                $start_date->format('Y-m-d'),
//                $end_date->format('Y-m-d')
//            ]);
//
//            if ($filter->categories_uuid) {
//                $builder->join('coin_categories', 'coin_categories.coin_uuid', '=', 'signals.coin_uuid');
//                $builder->whereIn('coin_categories.category_uuid', $filter->categories_uuid);
//
//                if (in_array('favorites', $filter->categories_uuid)) {
//                    $builder->leftJoin('user_favorites', 'user_favorites.coin_uuid', '=', 'signals.coin_uuid');
//                    $builder->orWhere('user_favorites.user_id', Auth::id());
//                }
//            }
//
//            if ($except_uuid) {
//                $builder->whereNotIn('signals.coin_uuid', $except_uuid);
//            }
//
//            $signals = $builder->get();
//    //        $signals = self::rankChangeCalculate($signals, $filter->signals);
//            $signals = self::rankChangeCalculate($signals, 0);
//            if (!$signals->count()) return $signals;
//
//            $changed_to = self::maxChangedRankByDay($signals, $end_date);
//            $signals_uuid = self::signalsChangedByRankByDate($changed_to, $end_date);
//
//            self::appendSignals($signals, $signals_uuid->toArray(), $signals->max('diff'), $filter->min_rank);
//
//            $signals = self::filterBySignals($signals, $filter->signals);
//
//            return self::sortBy(
//                $signals,
//                $sortable->column,
//                $sortable->direction
//            );
//        });
//    }

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
    private static function queryBuilder(): Builder
    {
        return DB::table('signals')
            ->join('coin_categories', 'coin_categories.coin_uuid', '=', 'signals.coin_uuid')
            ->distinct()
            ->select(['signals.coin_uuid', 'coin_categories.category_uuid'])
            ->selectRaw('FIRST_VALUE(`rank`) OVER (PARTITION BY signals.coin_uuid ORDER BY date ASC) previous_rank')
            ->selectRaw('FIRST_VALUE(`rank`) OVER (PARTITION BY signals.coin_uuid ORDER BY date DESC) current_rank');
    }

}
