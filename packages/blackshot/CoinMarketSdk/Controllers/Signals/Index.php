<?php

namespace Blackshot\CoinMarketSdk\Controllers\Signals;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\TrackingCoin;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Repositories\SignalRepository;
use Blackshot\CoinMarketSdk\Repositories\UserSettingsRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use stdClass;

class Index extends Controller
{
    /**
     *
     */
    const SORTABLE_SIGNAL_SETTINGS_KEY = 'signals_sortable_settings';

    /**
     *
     */
    const SORTABLE_BUYING_SETTINGS_KEY = 'buying_sortable_settings';

    /**
     * @param Request $request
     * @return View
     * @throws Exception
     */
    public function index(Request $request): View
    {
        $this->updateFilterAndSortable($request);

        /*  */
        $signals_sortable = $this->getSettingSortable(self::SORTABLE_SIGNAL_SETTINGS_KEY);
        $buying_sortable = $this->getSettingSortable(self::SORTABLE_BUYING_SETTINGS_KEY);

        /*  */
        $filter = $this->getFilter();

        /*  */
        $categories = CoinCategoryRepository::categoriesForSelect(Auth::user());

        /* @var Collection<Coin> $buying_coins */
        $buying_coins = Auth::user()->buyingCoins;
        $i_buy_uuid = $buying_coins ? $buying_coins->pluck('uuid') : null;

        /* @var Collection<object> */
        $signals = SignalRepository::coinCollection($filter, $signals_sortable, $i_buy_uuid);
        $coins = $this->viewCoins($signals, $signals_sortable);

        /* @var Collection<object> */
        $buying = SignalRepository::buyingCollection($filter, $i_buy_uuid);
        $coins_buy = $this->viewBuyCoins($buying, $buying_sortable);

        return view('blackshot::signals.index_new', [
            'signals' => $signals,
            'coins' => $coins,
            'coins_buying_me' => $coins_buy,
            'categories' => $categories,
            'filter' => $filter,
            'sortable' => [
                'signals' => $signals_sortable,
                'buying' => $buying_sortable
            ]
        ]);
    }

    /**
     * @param Collection|null $signals
     * @param object|null $sortable
     * @param int $per_page
     * @return LengthAwarePaginator|null
     */
    protected function viewCoins(Collection $signals = null, object $sortable = null, int $per_page = 25): ?LengthAwarePaginator
    {
        if (!$signals) return null;

        $signals_pagination = SignalRepository::pagination(
            $signals,
            $page = Paginator::resolveCurrentPage(),
            $per_page
        );

        $coins = Coin::whereIn('uuid', $signals_pagination->pluck('coin_uuid'))
            ->with(['info'])
            ->get();
        $coins = $this->mergeSignalsData($coins, $signals_pagination);

        if ($sortable) {
            $coins = $this->sortable($coins, $sortable);
        }


        return self::paginator($coins, $signals->count(), $per_page, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * @param Collection|null $signals
     * @param object|null $sortable
     * @return Collection
     */
    protected function viewBuyCoins(Collection $signals = null, object $sortable = null): Collection
    {
        if (!$signals) return collect();

        $coins = Coin::whereIn('uuid', $signals->pluck('coin_uuid'))->get();

        $coins = $this->mergeSignalsData($coins, $signals);

        return $sortable
            ? $this->sortable($coins, $sortable)
            : $coins;
    }

    /**
     * @param Collection $coins
     * @param Collection $signals
     * @return Collection
     */
    protected function mergeSignalsData(Collection $coins, Collection $signals): Collection
    {
        return $coins->map(function ($coin) use ($signals) {
            $signal = $signals->where('coin_uuid', $coin->uuid)->first();
            foreach ($signal as $key => $value) {
                if ($key != 'coin_uuid') {
                    $coin->$key = $value;
                }
            }

            return $coin;
        });
    }

    /**
     * @param Collection $collection
     * @param object $rules
     * @return Collection
     */
    protected function sortable(Collection $collection, object $rules): Collection
    {
        return $rules->direction === 'asc'
            ? $collection->sortBy($rules->column)->values()
            : $collection->sortByDesc($rules->column)->values();
    }

    /**
     * @param $items
     * @param $total
     * @param $perPage
     * @param $currentPage
     * @param $options
     * @return LengthAwarePaginator
     */
    protected static function paginator($items, $total, $perPage, $currentPage, $options): LengthAwarePaginator
    {
        return App::make(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }

    /**
     * @param Request $request
     * @return void
     */
    private function updateFilterAndSortable(Request $request): void
    {
        if ($request->has('sortable')) {
            $sortable = $request->get('sortable');

            $key = array_key_first($sortable) === 'signals'
                ? self::SORTABLE_SIGNAL_SETTINGS_KEY : self::SORTABLE_BUYING_SETTINGS_KEY;

            @list($column, $direction) = explode(':', array_shift($sortable));
            if (!isset($column) || !isset($direction)) {
                $column = 'rank';
                $direction = 'asc';
            }

            $this->updateSortableSettings($key, $column, $direction);
            redirect()->route('signals.home');
        }
    }

    /**
     * @return stdClass|null
     */
    private function getFilter(): ?stdClass
    {
        $filter = UserSettingsRepository::get('signal_filter') ?? null;

        if (!$filter) {
            $filter = new stdClass();
            $filter->days = 7;
            $filter->min_rank = 30;
            $filter->signals = [];
            $filter->categories_uuid = [];
            UserSettingsRepository::saveJson('signal_filter', (array) $filter);
        }

        return $filter;
    }







    /**
     * @param Request $request
     * @return View
     * @throws Exception
     */
//    public function index_last(Request $request): View
//    {
//        if ($request->has('sortable')) {
//            $sortable = $request->get('sortable');
//
//            $key = array_key_first($sortable) === 'signals'
//                ? self::SORTABLE_SIGNAL_SETTINGS_KEY : self::SORTABLE_BUYING_SETTINGS_KEY;
//
//            @list($column, $direction) = explode(':', array_shift($sortable));
//            if (!isset($column) || !isset($direction)) {
//                $column = 'rank';
//                $direction = 'asc';
//            }
//
//            $this->updateSortableSettings($key, $column, $direction);
//            return redirect()->route('signals.home');
//        }
//
//        /*  */
//        $categories = CoinCategoryRepository::categoriesForSelect();
//
//        /*  */
//        $filter = UserSettingsRepository::get('signal_filter') ?? null;
//        if (!$filter) {
//            $filter = new stdClass();
//            $filter->days = 7;
//            $filter->min_rank = 30;
//            $filter->signals = [];
//            UserSettingsRepository::saveJson('signal_filter', (array) $filter);
//        }
//
//        /*  */
//        $coins = $this->trackingCoins($filter);
//
//        if (!isset($filter->signals) || !isset($filter->days) || !isset($filter->min_rank) ) {
//            UserSettingsRepository::saveJson('signal_filter', [
//                'signals' => [],
//                'min_rank' => 30,
//                'days' => 7
//            ]);
//
//            redirect()->route('signals.home');
//        }
//
//        /* */
//        $start = (new DateTimeImmutable('now -'. $filter->days .' days'))
//            ->setTime(0,0);
//
//        $end = (new DateTimeImmutable('now'))
//            ->setTime(23, 59, 59);
//
//        /* @var HasManyThrough $my_buying_coins */
//        $my_buying_coins = Auth::user()->buyingCoins;
//
//        /* @var Coin $coin */
//        $coins->map(function ($coin) use ($my_buying_coins, $start, $end, $filter) {
//            $now = new DateTimeImmutable('now');
//
//            $quotes = $coin->quotesByDate($start, $end);
//
//            // сгруппировать изменения по дням
//            $coin->quotes_group_by_days = CoinRepository::groupByDate(
//                // рассчитать изменения позиций
//                CoinRepository::calculateRankChanged($quotes)
//            )->sortKeysDesc();
//
//            // изменения позиций
//            $start = $coin->quotes_group_by_days->first();
//            $end = $coin->quotes_group_by_days->last();
//
//            $coin->rank_diff = $end
//                ? $end->last()->cmc_rank - $start->last()->cmc_rank
//                : 0;
//
//            /* минусовые не показываем */
//            if ($coin->rank_diff < 0) $coin->rank_diff = 0;
//
//            $today = $coin->quotes_group_by_days->get($now->format('Y-m-d'));
//
//            // 1. самый большой прирост за день
//            $coin->max_up_today = false;
//            $coin->max_up_today_rank = $today
//                ? $today->max('cmc_rank_changed')
//                : 0;
//
//            // 2. самый большой прирост выбранный период
////            dump($coin->quotes_group_by_days);
//            $coin->max_up_period = false;
//            $coin->max_up_period_rank = $coin->quotes_group_by_days
//                ? $coin->quotes_group_by_days
//                    ->flatten()
//                    ->max('cmc_rank_changed')
//                : 0;
//
//            // 3. более 30 мест за неделю
//            $coin->more_30_rank = $coin->rank_diff >= $filter->min_rank;
//
//            // 4. Я покупаю эту монету
//            $coin->buying_me = (bool) $my_buying_coins->where('uuid', $coin->uuid)->count();
//        });
//
//        if ($coins->count()) {
//            $this->setKeyWhere(
//                $coins,
//                'max_up_today_rank',
//                $coins->max('max_up_today_rank') ?? 0,
//                'max_up_today'
//            );
//
//            $this->setKeyWhere(
//                $coins,
//                'max_up_period_rank',
//                $coins->max('max_up_period_rank') ?? 0,
//                'max_up_period'
//            );
//        }
//
//        // все монеты с сигналами
//        $filter_coins_signals_all = $coins->filter(function($coin) {
//            return $coin->more_30_rank || $coin->max_up_period || $coin->max_up_today;
//        });
//
//        // монеты соответствующие фильтру
//        $filter_coins_signals = $filter_coins_signals_all->filter(function($coin) use ($filter) {
//            if (isset($filter) && count($filter->signals)) {
//                foreach ($filter->signals as $key) {
//                    if ($coin->$key === true && !$coin->buying_me) return $coin;
//                }
//            }
//
//            return false;
//        });
//
//        /* sortable settings */
//        $signals_sortable_settings = $this->getSettingSortable(self::SORTABLE_SIGNAL_SETTINGS_KEY);
//        $buying_sortable_settings = $this->getSettingSortable(self::SORTABLE_BUYING_SETTINGS_KEY);
//
//        /* sort signals */
//        $signals = $this->sortableTable($filter_coins_signals, $signals_sortable_settings);
//
//        /* sort buying coins */
//        $buying = $this->sortableTable($coins->filter(function($coin) {
//            return $coin->buying_me;
//        }), $buying_sortable_settings);
//
//        /* categories */
//
//        return view('blackshot::signals.index', [
//            'coins' => $signals,
//            'coins_buying_me' => $buying,
//            'categories' => $categories,
//            'sortable' => [
//                'signals' => $signals_sortable_settings,
//                'buying' => $buying_sortable_settings
//            ],
//            'filter' => $filter,
//            'filter_counter' => [
//                'max_up_today' => $filter_coins_signals_all->where('max_up_today', true)->where('buying_me', false)->count(),
//                'max_up_period' => $filter_coins_signals_all->where('max_up_period', true)->where('buying_me', false)->count(),
//                'more_30_rank' => $filter_coins_signals_all->where('more_30_rank', true)->where('buying_me', false)->count(),
//            ]
//        ]);
//    }

    /**
     * @param string $key
     * @return null
     */
    private function getSettingSortable(string $key): ?stdClass
    {
        $settings = UserSettingsRepository::get($key);

        if (!$settings) {
            $this->updateSortableSettings($key, 'rank', 'asc');

            $settings = new stdClass();
            $settings->column = 'rank';
            $settings->direction = 'asc';

            return $settings;
        }

        return $settings;
    }

//    private function trackingCoins(stdClass $filter = null)
//    {
//        $coins = Coin::select('coins.*')
//            ->with(['info', 'quotes'])
////            ->whereIn('uuid', Auth::user()->favorites()->pluck('coin_uuid'))
//            ->whereIn('uuid', TrackingCoin::where('user_id', Auth::id())->pluck('coin_uuid'));
//
////        $coins = Coin::select('coins.*')->limit(10)->with(['info', 'quotes']);
//
//        if (isset($filter->category_uuid) && $filter->category_uuid) {
//            $coins->join('coin_categories', 'coins.uuid', 'coin_categories.coin_uuid');
//            $coins->whereIn('coin_categories.category_uuid', $filter->category_uuid);
//
//            if (in_array('favorites', $filter->category_uuid)) {
//                $favorites = Auth::user()->favorites;
//                $coins->orWhereIn('uuid', $favorites->pluck('uuid'));
//                $coins->distinct();
//            }
//        }
//
//        return $coins->get();
//    }

    /**
     * @param string $key
     * @param string $column
     * @param string $direction
     * @return void
     */
    private function updateSortableSettings(string $key, string $column, string $direction): void
    {
        if (!in_array($column, ['rank', 'diff'])) $column = 'rank';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'asc';

        UserSettingsRepository::saveJson($key, [
            'column' => $column,
            'direction' => $direction
        ]);
    }

    /**
     * @param Collection $collection
     * @param stdClass $sortable
     * @return Collection
     */
//    private function sortableTable(Collection $collection, stdClass $sortable): Collection
//    {
//        if ($sortable->direction === 'asc') {
//            return $collection->sortBy($sortable->column);
//        }
//
//        return $collection->sortByDesc($sortable->column);
//    }

    /**
     * @param Collection $collection
     * @param string $search_key
     * @param string $value
     * @param string $set_key_bool
//     */
//    private function setKeyWhere(Collection &$collection, string $search_key, string $value, string $set_key_bool)
//    {
//        $collection->map(function($item) use ($search_key, $value, $set_key_bool) {
//            $item->$set_key_bool = $item->$search_key == $value;
//        });
//    }
}
