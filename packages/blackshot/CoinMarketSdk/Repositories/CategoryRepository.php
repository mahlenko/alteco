<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\CategoryMarketModel;
use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\CategoryVolumeModel;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class CategoryRepository
{
    /**
     * @param string $id
     * @param string $name
     * @param string|null $title
     * @param string|null $description
     * @param int|null $num_tokens
     * @param float|null $avg_price_change
     * @param float|null $market_cap
     * @param float|null $market_cap_change
     * @param float|null $volume
     * @param float|null $volume_change
     * @param DateTimeImmutable|null $last_updated
     * @return CategoryModel
     * @throws Exception
     */
    public static function createOrUpdate(
        string $id,
        string $name,
        string $title = null,
        string $description = null,
        int $num_tokens = null,
        float $avg_price_change = null,
        float $market_cap = null,
        float $market_cap_change = null,
        float $volume = null,
        float $volume_change = null,
        DateTimeImmutable $last_updated = null
    ): CategoryModel
    {
        $category = CategoryModel::where('id', $id)->first();

        if (!$category) {
            $uuid = Uuid::uuid4();

            $category = new CategoryModel();
            $category->uuid = $uuid;
        }

        $category->fill([
            'id' => $id,
            'type' => self::getCategoryType($name),
            'name' => $name,
            'title' => $title,
            'description' => $description,
            'num_tokens' => $num_tokens,
            'avg_price_change' => $avg_price_change,
            'last_updated' => $last_updated->format('Y-m-d H:i:s')
        ]);

        $category->save();

        if (isset($uuid) && empty($category->uuid)) {
            $category->uuid = $uuid->toString();
        }

        $current_last_update = (new DateTimeImmutable($category->last_updated))
            ->format('Y-m-d H:i:s');

        if (!$category->markets->count() || $last_updated->format('Y-m-d H:i:s') != $current_last_update) {
            (new CategoryMarketModel([
                'category_uuid' => $category->uuid,
                'cap' => $market_cap,
                'cap_change' => $market_cap_change
            ]))->save();
        }

        if (!$category->volumes->count() || $last_updated->format('Y-m-d H:i:s') != $current_last_update) {
            (new CategoryVolumeModel([
                'category_uuid' => $category->uuid,
                'volume' => $volume,
                'volume_change' => $volume_change
            ]))->save();
        }

        return $category;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getCategoryType(string $name): string
    {
        return Str::contains(Str::lower($name), 'portfolio')
            ? CategoryModel::TYPE_FOUNDS
            : CategoryModel::TYPE_OTHER;
    }

}
