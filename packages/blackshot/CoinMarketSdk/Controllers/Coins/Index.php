<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Models\User;
use Blackshot\CoinMarketSdk\Enums\BannerTypes;
use Blackshot\CoinMarketSdk\Models\Banner;
use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Repositories\UserSettingsRepository;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Index extends \App\Http\Controllers\Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        $sortable = $this->sortable($request);

        $filter = UserSettingsRepository::get('coins_filter');
        $date_period = $this->filterDatePeriod($filter);

        /*  */
        $auth = Auth::user();

        /*  */
        $per_page = 25;

        $allow_categories = CoinCategoryRepository::categoriesForSelect(Auth::user());

        $coins = $this->getCoinsFiltered($allow_categories, $sortable, $filter, $auth);
        $coins_signals = $this->getPeriodRank($coins, $date_period, $sortable, $per_page);
        $viewCoins = $this->getViewCoins($coins, $coins_signals, $sortable, $per_page);

        if (Auth::check() && Auth::user()->tariff->isFree()) {
            $now = new DateTimeImmutable();
            $banners = Banner::where('is_active', true)
                ->where('type', BannerTypes::static->name)
                ->where('start', '<=', $now)
                ->where(function($builder) use ($now) {
                    $builder->where('end', null);
                    $builder->orWhere('end', '<=', $now);
                })->get();

            if ($banners) {
                $banners = $banners->shuffle()->take(2);
            }
        }

        return view('blackshot::coins.index', [
            'coins' => $viewCoins,
            'categories' => $allow_categories,
            'filter' => $filter,
            'sortable' => $sortable,
            'favorites' => $auth->favorites,
            'tracking' => $auth->trackings,
            'change' => $date_period,
            'change_diff' => $date_period[0]->diff($date_period[1]),
            'banners' => $banners ?? collect()
        ]);
    }

    /**
     * @param Collection $allow_categories
     * @param array $sortable
     * @param null $filter
     * @param User|null $user
     * @return Builder
     */
    public function getCoinsFiltered(
        Collection $allow_categories,
        array $sortable,
        $filter = null,
        User $user = null
    ): Builder
    {
        // @todo вынести функцию отдельно
//        $allow_categories_uuid = $allow_categories
//            ->only([CategoryModel::TYPE_OTHER, CategoryModel::TYPE_FOUNDS])
//            ->mapWithKeys(function($item) {
//                return $item;
//            })->keys();
//
//        if (!empty($filter->category_uuid)) {
//            $diff = $allow_categories_uuid->diff($filter->category_uuid);
//            $_temp = $allow_categories_uuid->diff($diff);
//            if ($_temp->count()) {
//                $allow_categories_uuid = $_temp;
//            }
//        }
        // end


        /* @var Coin $coins */
        $coins = Coin::select('coins.*');

        if (empty($filter->q) && empty($filter->category_uuid)) {
            if ($sortable['column'] != 'rank_period') {
                return $sortable['direction'] == 'asc'
                    ? $coins->orderBy($sortable['column'])
                    : $coins->orderByDesc($sortable['column']);
            }

            return $coins;
        }

        // Фильтр по названию
        if (!empty($filter->q)) {
            $coins->where(function ($table) use ($filter) {
                $table->where('name', 'like', '%' . $filter->q . '%');
                $table->orWhere('symbol', '=', $filter->q);
            });
        }

        //
        if (!empty($filter->category_uuid)) {
            $coins->join('coin_categories', 'coin_categories.coin_uuid', '=', 'coins.uuid');
            $coins->distinct();

            if (in_array('favorites', $filter->category_uuid)) {
                $coins->where(function ($table) use ($filter, $user) {
                    $table->whereIn('coin_categories.category_uuid', $filter->category_uuid);
                    $table->orWhereIn('coins.uuid', $user->favorites->pluck('uuid'));
                });
            } else {
                $coins->whereIn('coin_categories.category_uuid', $filter->category_uuid);
            }
        }

        if ($sortable['column'] == 'rank_period') {
            return $coins;
        }

        return $sortable['direction'] == 'asc'
            ? $coins->orderBy('coins.'.$sortable['column'])
            : $coins->orderByDesc('coins.'.$sortable['column']);
    }

    /**
     * @param Builder $coins
     * @param array $dates
     * @param array $sortable
     * @param int $per_page
     * @return Collection
     */
    public function getPeriodRank(
        Builder $coins,
        array $dates,
        array $sortable,
        int $per_page = 25
    ): Collection
    {
        $builder_signals = CoinRepository::signalsPeriodBuilder(
            $dates[0]->format('Y-m-d'),
            $dates[1]->format('Y-m-d')
        );

        // если не сортируем по rank_period достаточно взять данные по 25 монетам
        if ($sortable['column'] !== 'rank_period') {
            $coins = $coins->forPage(Paginator::resolveCurrentPage(), $per_page);
            $builder_signals->whereIn('coin_uuid', $coins->pluck('uuid'));
        }

        $signals = $builder_signals->get();

        return $signals->map(function($signal) {
            $signal->rank_period = $signal->previous_rank - $signal->current_rank;
            return $signal;
        });
    }

    /**
     * @param Builder $coins
     * @param Collection $signals
     * @param array $sortable
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    public function getViewCoins(
        Builder $coins,
        Collection $signals,
        array $sortable,
        int $per_page = 25
    ): LengthAwarePaginator
    {
        if ($sortable['column'] !== 'rank_period') {
            $viewCoins = $coins->with(['info'])->paginate($per_page);

            $viewCoins->getCollection()->transform(function ($item) use ($signals) {
                $signal = $signals->where('coin_uuid', $item->uuid)->first();

                $item->rank_period = $signal->rank_period ?? null;
                return $item;
            });

            return $viewCoins;
        }

        // сортировка по rank_period
        $viewCoins = $coins->get();

        $viewCoins->map(function ($item) use ($signals) {
            $signal = $signals->where('coin_uuid', $item->uuid)->first();
            $item->rank_period = $signal ? $signal->rank_period : null;
        });

        $viewCoins = $sortable['direction'] == 'asc'
            ? $viewCoins->sortBy($sortable['column'])
            : $viewCoins->sortByDesc($sortable['column']);

        $pagination_items = $viewCoins->forPage(
            $page = Paginator::resolveCurrentPage(),
            $per_page
        );

        return self::paginator($pagination_items, $viewCoins->count(), $per_page, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * @param $filter
     * @return array<Carbon>
     */
    public function filterDatePeriod($filter): array
    {
        if (!$filter) {
            $filter_date = [
                Carbon::createFromTimestamp(strtotime('-7 day')),
                Carbon::createFromTimestamp(strtotime('now'))
            ];
        } else {
            $filter_date = [
                Carbon::createFromTimestamp(strtotime($filter->date[0])),
                Carbon::createFromTimestamp(strtotime($filter->date[1]))
            ];
        }

        return $filter_date;
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
     * @return array
     */
    private function sortable(Request $request): array
    {
        $column = 'rank';
        $direction = 'asc';

        //
        if ($request->has('sortable') && !empty($request->get('sortable'))) {
            if (Str::contains($request->get('sortable'), ',')) {
                list($column, $direction) = explode(',', $request->get('sortable'));
            } else {
                $column = $request->get('sortable');
                $direction = 'asc';
            }
        }

        return [
            'column' => $column,
            'direction' => $direction
        ];
    }
}
