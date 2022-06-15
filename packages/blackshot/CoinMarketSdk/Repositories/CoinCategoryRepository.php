<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\CoinCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CoinCategoryRepository
{
    /**
     * @return Collection
     */
    static function allCategories(): Collection
    {
//        return CategoryModel::join('coin_categories', 'coin_categories.category_uuid', '=', 'categories.uuid')
//            ->select('categories.*')
//            ->groupBy('coin_categories.category_uuid')
//            ->get();

        // 30 минут
        return Cache::remember('allCategories', time() + 1200, function() {
            return CategoryModel::all();
        });
    }

    /**
     * @return Collection
     */
    static function categoriesForSelect(): Collection
    {
        $categories = self::allCategories()
            ->groupBy('type')
            ->sortKeysUsing(function($key) {
                return $key != CategoryModel::TYPE_FOUNDS ? 1 : 0;
            })->map(function($collection) {
                return $collection->pluck('name', 'uuid');
            });

        return collect(['favorites' => '- MY FAVORITES -'])
            ->merge($categories);
    }

    /**
     * @param Coin $coin
     * @param CategoryModel $category
     * @return CoinCategory
     */
    static function relation(Coin $coin, CategoryModel $category): CoinCategory
    {
        $unique = CoinCategory::where([
            'coin_uuid' => $coin->uuid,
            'category_uuid' => $category->uuid
        ])->first();

        if ($unique) return $unique;

        $relation = new CoinCategory();
        $relation->coin_uuid = $coin->uuid;
        $relation->category_uuid = $category->uuid;
        $relation->save();

        return $relation;
    }
}
