<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\CoinCategory;
use Blackshot\CoinMarketSdk\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CoinCategoryRepository
{
    /**
     * @return Collection
     */
    static function allCategories(): Collection
    {
        // 12 часов
        return Cache::remember('categories', time() + 60 * 60 * 12, function() {
            return CategoryModel::all();
        });
    }

    /**
     * @param Authenticatable|User $user
     * @return Collection
     */
    static function categoriesForSelect(Authenticatable|User $user): Collection
    {
//        $isFree = !$user->isAdmin() && $user->tariff->isFree();

        $categories = self::allCategories()
            ->groupBy('type')
            ->sortKeysUsing(function($key) {
                return $key != CategoryModel::TYPE_FOUNDS ? 1 : 0;
            })->map(function($collection) {
                return $collection->pluck('name', 'uuid');
            });

        // Закрытие фондов в бесплатном тарифе
        if (!$user->isSubscribe()) {
            /* @var Collection $founds */
            $founds = $categories[CategoryModel::TYPE_FOUNDS]->take(3);

            $other_count = $categories[CategoryModel::TYPE_FOUNDS]->count() - 3;
            $founds_text_choice = trans_choice('фонду|фондам|фондам', $other_count);
            $founds->put('subscribe', sprintf('<span class="title">Оформить подписку</span><span class="description">Получить доступ к еще %d %s</span>', $other_count, $founds_text_choice));
            $founds = $founds->merge($categories[CategoryModel::TYPE_FOUNDS]->skip(3));

            $categories[CategoryModel::TYPE_FOUNDS] = $founds;
        }

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
