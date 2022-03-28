<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\CoinInfo;
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
     * @param stdClass|array|null $tags
     * @param stdClass|array|null $urls
     * @return CoinInfo
     */
    public static function create(
        Coin $coin,
        string $category,
        string $logo = null,
        string $description = null,
        string $notice = null,
        DateTimeImmutable $date_added = null,
        $tags = null,
        $urls = null
    ): CoinInfo
    {
        /* @var CoinInfo $info */
        if ($info = self::getByUuid($coin->uuid)) {
            // update tags
            if ($tags) {
                foreach ($tags as $tag) {
                    $info->attachTag($tag);
                }
            }

            // added links
            if ($urls) {
                foreach ($urls as $type => $urls) {
                    foreach ($urls as $url) {
                        $info->attachUrl($type, $url);
                    }
                }
            }

            return $info;
        }

        $info = new CoinInfo();
        $info->fill([
            'coin_uuid' => $coin->uuid,
            'category' => $category,
            'logo' => $logo,
            'description' => $description,
            'notice' => $notice,
            'date_added' => $date_added->format('Y-m-d H:i:s')
        ])->save();

        // attach tags
        if ($tags) foreach ($tags as $tag) $info->attachTag($tag);

        // added links
        if ($urls) {
            foreach ($urls as $type => $urls) {
                foreach ($urls as $url) {
                    $info->attachUrl($type, $url);
                }
            }
        }

        return $info;
    }

    public static function update()
    {}
}
