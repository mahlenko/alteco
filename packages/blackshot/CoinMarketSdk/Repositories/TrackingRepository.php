<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\TrackingCoin;
use Blackshot\CoinMarketSdk\Models\User;

class TrackingRepository
{

    /**
     * @param Coin $coin
     * @param User $user
     * @return TrackingCoin
     */
    public static function add(Coin $coin, User $user): TrackingCoin
    {
        return TrackingCoin::create([
            'coin_uuid' => $coin->uuid,
            'user_id' => $user->id
        ]);
    }

    /**
     * @param Coin $coin
     * @param User $user
     * @return bool
     */
    public static function remove(Coin $coin, User $user): bool
    {
        return TrackingCoin::where([
            'coin_uuid' => $coin->uuid,
            'user_id' => $user->id
        ])->delete();
    }

    /**
     * @param Coin $coin
     * @param User $user
     * @return bool|string
     */
    public static function toggle(Coin $coin, User $user)
    {
        if (self::search($coin->uuid, $user->id)) {
            return self::remove($coin, $user) ? 'delete' : false;
        }

        return self::add($coin, $user) ? 'add' : false;
    }

    /**
     * @param string $uuid
     * @param int $user_id
     * @return TrackingCoin|null
     */
    public static function search(string $uuid, int $user_id): ?TrackingCoin
    {
        return TrackingCoin::where([
            'coin_uuid' => $uuid,
            'user_id' => $user_id
        ])->first();
    }

}
