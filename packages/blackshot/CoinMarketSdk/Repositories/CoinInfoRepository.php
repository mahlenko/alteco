<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\CoinInfo;
use Blackshot\CoinMarketSdk\Models\CoinUrl;
use Blackshot\CoinMarketSdk\Models\Tags;
use DateTimeImmutable;
use stdClass;

class CoinInfoRepository
{
    /**
     * @param string $uuid
     * @return CoinInfo|null
     */
    public static function getByUuid(string $uuid): ?CoinInfo
    {
        return CoinInfo::where('coin_uuid', $uuid)->first();
    }

    /**
     * @param Coin $coin
     * @param string $category
     * @param string|null $logo
     * @param string|null $description
     * @param string|null $notice
     * @param DateTimeImmutable|null $date_added
     * @param array|stdClass|null $tags
     * @param array|stdClass|null $urls
     * @return CoinInfo
     */
    public static function create(
        Coin              $coin,
        string            $category,
        string            $logo = null,
        string            $description = null,
        string            $notice = null,
        DateTimeImmutable $date_added = null,
        array|stdClass    $tags = null,
        array|stdClass    $urls = null
    ): CoinInfo
    {
        if (!$coin->info) {
            $coin->info()->create([
                'category' => $category
            ]);
        }

        $data = [
            'logo' => $logo,
            'description' => $description,
            'notice' => $notice,
            'date_added' => $date_added
        ];

        if (!empty($data['description']) && !empty($coin->info->description)) {
            unset($data['description']);
        }

        $coin->info->fill($data)->save();

        //
        if ($tags && count($tags)) {
            foreach ($tags as $tag_name) self::addTag($coin->info, $tag_name);
        }

        //
        if ($urls && count((array) $urls)) {
            foreach ($urls as $type => $items) {
                foreach ($items as $url) {
                    self::addUrl($coin->info, $type, $url);
                }
            }
        }

        return $coin->info;
    }

    /**
     * @param CoinInfo $coinInfo
     * @param string $tag_name
     * @return Tags
     */
    public static function addTag(CoinInfo $coinInfo, string $tag_name): Tags
    {
        $tag_from_db = Tags::where([
            'coin_uuid' => $coinInfo->coin_uuid,
            'name' => $tag_name
        ])->first();

        if (!$tag_from_db) {
            $tag_from_db = $coinInfo->tags()->create([
                'coin_uuid' => $coinInfo->coin_uuid,
                'name' => $tag_name
            ]);
        }

        return $tag_from_db;
    }

    /**
     * @param CoinInfo $coinInfo
     * @param string $type
     * @param string $url
     * @return CoinUrl
     */
    public static function addUrl(CoinInfo $coinInfo, string $type, string $url): CoinUrl
    {
        /* @var CoinUrl $url_from_db */
        $url_from_db = $coinInfo->urls
            ->where('type', $type)
            ->where('url', $url .'as')
            ->first();


        if (!$url_from_db) {
            $url_from_db = $coinInfo->urls()->create([
                'coin_uuid' => $coinInfo->coin_uuid,
                'type' => $type,
                'url' => $url
            ]);
        }

        return $url_from_db;
    }

    public static function update()
    {}
}
