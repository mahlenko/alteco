<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\TrackingCoin;
use Blackshot\CoinMarketSdk\Models\UserFavorites;

class FavoritesRepository
{
    /**
     * @param string $coin_uuid
     * @param int $user_id
     * @return bool
     */
    public static function favorite(string $coin_uuid, int $user_id): bool
    {
        $favorite = UserFavorites::where([
            'user_id' => $user_id,
            'coin_uuid' => $coin_uuid
        ])->first();

        if ($favorite) {
            $favorite->delete();
            return false;
        }

        UserFavorites::create([
            'user_id' => $user_id,
            'coin_uuid' => $coin_uuid
        ]);

        return true;
    }

}
