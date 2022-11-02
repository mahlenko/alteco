<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use Blackshot\CoinMarketSdk\Commands\CoinExponentialRankCommand;
use Blackshot\CoinMarketSdk\Models\Banner;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Repositories\SignalRepository;
use Blackshot\CoinMarketSdk\Repositories\UserSettingsRepository;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use stdClass;

class Index extends Controller
{
    const PER_PAGE = 25;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     * @throws \Exception
     */
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        /* @var Authenticate|User $user */
        $user = Auth::user();
        $favorites = $user->favorites()
            ->select('coin_uuid')
            ->pluck('coin_uuid');

        $filter = $this->userFilter();

        $categories = CoinCategoryRepository::categoriesForSelect($user);
        $sortable = self::sortable($request);

        $coins = self::emaCalculate(
            self::filterCoinsList($user, $filter),
            self::signalsCache($filter->date)
        );

        if (in_array($sortable['column'], ['rank_30d', 'rank_60d', 'rank_period', 'alpha', 'squid', 'beta', 'exponential_rank_period'])) {
            $coins = $coins->whereNotNull($sortable['column']);
        }

        /* Сортировка монет */
        if ($coins->count()) {
            $coins = $coins->sortBy(
                $sortable['column'],
                SORT_REGULAR,
                $sortable['direction'] == 'desc'
            );
        }

        return view('blackshot::coins.index', [
            'coins' => self::paginate($coins),
            'categories' => $categories,
            'filter' => $filter,
            'sortable' => $sortable,
            'favorites' => $favorites,
            'promo' => $user->tariff->isFree()
                ? self::bannerActive()
                : null
        ]);
    }

    /**
     * @param Authenticate|User $user
     * @param object|null $filter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private static function filterCoinsList(Authenticate|User $user, object $filter = null): \Illuminate\Database\Eloquent\Collection
    {
        $coins = CoinRepository::handle();

        /* Фильтр по названию */
        if (!is_null($filter->q) && !empty(trim($filter->q))) {
            $query = Str::lower($filter->q);
            $coins = $coins->filter(function(Coin $coin) use ($query) {
                return Str::lower($coin->symbol) == $query
                    || Str::contains(Str::lower($coin->name), $query);
            });
        }

        /* Фильтр по категориям */
        if (isset($filter->category_uuid)) {
            $categories = $filter?->category_uuid ?? null;
            $favorites_coin_uuid = [];

            /* Избранные монеты */
            if ($categories && in_array('favorites', $categories)) {
                $favorites_coin_uuid = $user->favoritesUuids
                    ->pluck('coin_uuid')
                    ->toArray();

                unset($categories[array_search('favorites', $categories)]);
            }

            $coins = $coins->filter(function(Coin $coin) use ($categories, $favorites_coin_uuid) {
                return in_array($coin->uuid, $favorites_coin_uuid) // монета есть в избранном
                    || $coin->categories->whereIn('uuid', $categories)->count(); // монета есть в категории
            });
        }

        return $coins;
    }

    /**
     * @param Collection $coins
     * @param array $signals
     * @return Collection
     */
    private static function emaCalculate(Collection $coins, array $signals): Collection
    {
        $coins = $coins->whereIn('uuid', array_keys($signals));

        return $coins->each(function($coin) use ($signals) {
            $coin->exponential_rank_period = CoinExponentialRankCommand::ema($signals[$coin->uuid]);
            return $coin;
        });
    }

    /**
     * @param array $period
     * @return array
     */
    private static function signalsCache(array $period): array
    {
        $signals = SignalRepository::handle(period: $period);

        return $signals->sortBy('date')->mapWithKeys(function($items, $coin_uuid) {
            return [ $coin_uuid => $items->pluck('rank') ];
        })->toArray();
    }

    /**
     * @param Request $request
     * @return array
     */
    private static function sortable(Request $request): array
    {
        $column = 'rank';
        $direction = 'asc';

        //
        if ($request->has('sortable') && !empty($request->get('sortable'))) {
            if (Str::contains($request->get('sortable'), ',')) {
                list($column, $direction) = explode(',', $request->get('sortable'));
            } else {
                $column = $request->get('sortable');
            }
        }

        return [
            'column' => $column,
            'direction' => $direction
        ];
    }

    /**
     * @param Collection $items
     * @return LengthAwarePaginator
     */
    private static function paginate(Collection $items): LengthAwarePaginator
    {
        $paginate = $items->forPage(
            $current_page = Paginator::resolveCurrentPage(),
            self::PER_PAGE
        );

        return App::make(
            LengthAwarePaginator::class,
            [
                'items' => $paginate,
                'total' => $items->count(),
                'perPage' => self::PER_PAGE,
                'currentPage' => $current_page,
                'options' => [
                    'path' => Paginator::resolveCurrentPath(),
                    'pageName' => 'page',
                    'fragment' => null,
                    'query' => request()->toArray()
                ]
            ]
        );
    }

    /**
     * @return Banner|null
     */
    public static function bannerActive(): ?Banner
    {
        $banners = Banner::activeNow()
            ->inRandomOrder()
            ->get();

        if (!$banners->count()) return null;
        return $banners->random();
    }

    /**
     * @param $filter
     * @return array<Carbon>
     */
    private static function periodCarbonConverter($filter): array
    {
        if ($filter) {
            return [
                Carbon::createFromTimestamp(strtotime($filter->date[0])),
                Carbon::createFromTimestamp(strtotime($filter->date[1]))
            ];
        }

        return [
            Carbon::createFromTimestamp(strtotime('-7 day')),
            Carbon::createFromTimestamp(strtotime('now'))
        ];
    }

    /**
     * @return object
     * @throws \Exception
     */
    private function userFilter(): object
    {
        $filter = UserSettingsRepository::get('coins_filter');
        if ($filter) {
            foreach ($filter->date as $key => $value) {
                $filter->date[$key] = new DateTimeImmutable($value);
            }

            return $filter;
        }

        $dateStart = (new DateTimeImmutable('-6 days'))->setTime(0, 0);
        $dateEnd = (new DateTimeImmutable('now'))->setTime(23, 59);

        $value = new stdClass();
        $value->q = null;
        $value->date = [ $dateStart, $dateEnd ];

        return $value;
    }
}
