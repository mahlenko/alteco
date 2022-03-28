<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\CoinCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CoinCategoryRepository
{
    /**
     * @return Collection
     */
    static function allCategories(): Collection
    {
        return CategoryModel::join('coin_categories', 'coin_categories.category_uuid', '=', 'categories.uuid')
            ->select('categories.*')
            ->groupBy('coin_categories.category_uuid')
            ->get();
    }

    /**
     * @return Collection
     */
    static function categoriesForSelect(): Collection
    {
        $categories = self::allCategories();

        return collect(['favorites' => '- MY FAVORITES -'])
            ->merge($categories->pluck('name', 'uuid'));
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
