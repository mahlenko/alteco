<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Models\User;
use Blackshot\CoinMarketSdk\Commands\ExponentialRank;
use Blackshot\CoinMarketSdk\Models\Banner;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Repositories\UserSettingsRepository;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use stdClass;

class Index extends Controller
{
    const PER_PAGE = 25;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request): \Illuminate\Contracts\View\View
    {
        /* @var Authenticate|User $user */
        $user = Auth::user();

        /* @var object $filter */
        $filter = UserSettingsRepository::get('coins_filter') ?? self::filterDefault();

        $user_key_cache = 'coins:user_'.$user->id.':filter_' . md5(json_encode($filter));

        $categories = CoinCategoryRepository::categoriesForSelect($user);
        $sortable = self::sortable($request);
        $period = $this->filterDatePeriod($filter);
        $coins = self::merged(
            Cache::remember($user_key_cache, time() + 300, function() use ($user, $filter) {
                return self::coins($user, $filter);
            }),
            self::ranks($period)
        );

        // Исключить записи которые не изменились (ранг, эксп. ранг например)
        if (in_array($sortable['column'], ['rank_30d', 'rank_60d', 'rank_period', 'exponential_rank_period'])) {
            $coins = $coins->where($sortable['column'], '<>', null);
        }

        if ($coins->count()) {
            $coins = $coins->sortBy(
                $sortable['column'],
                SORT_NATURAL,
                $sortable['direction'] == 'desc'
            );
        }

        return view('blackshot::coins.index', [
            'coins' => self::paginate($coins, self::PER_PAGE),
            'categories' => $categories,
            'filter' => $filter,
            'sortable' => $sortable,
            'favorites' => $user->favorites ?? collect(),
            'change' => $period,
            'change_diff' => $period[0]->diff($period[1]),
            'promo' => $user->tariff->isFree()
                ? self::bannerActive()
                : null
        ]);
    }

    private function merged(
        \Illuminate\Database\Eloquent\Collection $coins,
        Collection $ranks
    ): \Illuminate\Database\Eloquent\Collection|Collection {
        $coin_ranks = $ranks->whereIn('coin_uuid', $coins->pluck('uuid'));

        return $coins->map(function($coin) use ($coin_ranks) {
            $rank = $coin_ranks->where('coin_uuid', $coin->uuid)->first();

            $coin->rank_period = $rank['rank'] ?? 0;
            $coin->exponential_rank_period = $rank['exponential'] ?? 0;
            return $coin;
        });
    }

    /**
     * @param Authenticate|User $user
     * @param object|null $filter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private static function coins(Authenticate|User $user, object $filter = null): \Illuminate\Database\Eloquent\Collection
    {
        $categories = $filter?->category_uuid ?? null;

        $coins = Coin::select('coins.*');
        $coins->whereBetween('coins.rank', [1, 1000]);
        $coins->limit(1000);

        if ($categories) {
            $coins->whereHas('categories', function ($builder) use ($user, $categories) {
                if (in_array('favorites', $categories)) {
                    return $builder->where(function ($builder) use ($user, $categories) {
                        $favorites_coin_uuid = $user->favorites->pluck('uuid');
                        $builder->whereIn('coin_categories.category_uuid', $categories);
                        $builder->orWhereIn('coins.uuid', $favorites_coin_uuid);
                    });
                }

                return $builder->whereIn('category_uuid', $categories);
            });
        }

        if (!empty(trim($filter->q))) {
            $query = trim($filter->q);
            $coins->where(function ($table) use ($query) {
                $table->where('name', 'like', '%' . $query . '%');
                $table->orWhere('symbol', '=', $query);
            });
        }

        return $coins->get();
    }

    /**
     * Рассчитает экспоненциальный ранк за период
     */
    private static function ranks(array $period): Collection
    {
        $period_date = [
            $period[0]->format('Y-m-d 00:00:00'),
            $period[1]->format('Y-m-d 23:59:59')
        ];

        return Cache::remember(
            key: 'exponential_ranks:' . md5(json_encode($period_date)),
            ttl: time() + (60 * 60),
            callback: function() use ($period_date) {

                $ranks = Signal::select(['signals.coin_uuid', 'signals.rank', 'signals.diff', 'signals.date'])
                    ->join('coins', 'coins.uuid', 'signals.coin_uuid')
                    ->whereBetween('coins.rank', [1, 1000])
                    ->whereBetween('date', $period_date)
                    ->orderBy('date')
                    ->get()
                    ->groupBy('coin_uuid');

                return $ranks->map(function($group) {
//                    dd($group);
                    return collect([
                        'coin_uuid' => $group->first()->coin_uuid,
                        'rank' => $group->first()->rank - $group->last()->rank,
                        'exponential' => ceil(ExponentialRank::exponentialRank($group->pluck('rank')))
                    ]);
                })->values();
            });
    }

    /**
     * @param $filter
     * @return array<Carbon>
     */
    private static function filterDatePeriod($filter): array
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
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    private static function paginate(Collection $items, $per_page): LengthAwarePaginator
    {
        $paginate = $items->forPage(
            $current_page = Paginator::resolveCurrentPage(),
            $per_page
        )->load('info')->values();

        return App::make(
            LengthAwarePaginator::class,
            [
                'items' => $paginate,
                'total' => $items->count(),
                'perPage' => $per_page,
                'currentPage' =>  $current_page,
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

    private function filterDefault(): object
    {
        $now = new DateTimeImmutable();

        $value = new stdClass();
        $value->q = null;
        $value->date = [
            $now->modify('-6 days')->format('Y-m-d 00:00:00'),
            $now->format('Y-m-d 23:59:59'),
        ];

        return $value;
    }
}
