<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Models\User;
use Blackshot\CoinMarketSdk\Commands\ExponentialRank;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Repositories\UserSettingsRepository;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        $filter = UserSettingsRepository::get('coins_filter');

        $user_key_cache = 'Coins:'.$user->id.':filter:' . md5(json_encode($filter));

        $categories = CoinCategoryRepository::categoriesForSelect($user);
        $sortable = self::sortable($request);
        $period = $this->filterDatePeriod($filter);
        $coins = self::merged(
            Cache::remember($user_key_cache, time() + 1800, function() use ($user, $filter) {
                return self::coins($user, $filter);
            }),
            self::ranks($period)
        );

        $coins = $coins->where($sortable['column'], '<>', null);

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
//            'tracking' => $user->trackings,
            'change' => $period,
            'change_diff' => $period[0]->diff($period[1]),
            'banners' => $banners ?? collect()
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
     * @param object $filter
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private static function coins(Authenticate|User $user, object $filter): \Illuminate\Database\Eloquent\Collection
    {
        $categories = $filter->category_uuid ?? null;

        $coins = Coin::select('*');

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

        $ranks = Signal::select(['coin_uuid', 'rank', 'diff', 'date'])
            ->whereBetween('date', $period_date)
            ->orderBy('date')
            ->get()
            ->groupBy('coin_uuid');

        return Cache::remember(
            key: 'expRank:' . implode('_', $period_date),
            ttl: time() + (60 /** 60*/),
            callback: function() use ($ranks) {
                return $ranks->map(function($group) {
                    return collect([
                        'coin_uuid' => $group->first()->coin_uuid,
                        'rank' => $group->first()->rank - $group->last()->rank,
                        'exponential' => max(1, ExponentialRank::exponential($group->pluck('rank')))
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
        )->values();

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
}
