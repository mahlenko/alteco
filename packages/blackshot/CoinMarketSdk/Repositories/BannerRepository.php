<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\Banner;
use Blackshot\CoinMarketSdk\Models\User;
use DateTimeImmutable;
use DomainException;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;

class BannerRepository
{

    /**
     * @param array $data
     * @param Authenticatable|User $user
     * @return Banner
     * @throws Exception
     */
    public static function store(array $data, Authenticatable|User $user): Banner
    {
        return self::update(Banner::findOrNew($data['uuid']), $data, $user);
    }

    /**
     * @param Banner $banner
     * @param array $data
     * @param Authenticatable|User $user
     * @return Banner
     * @throws Exception
     */
    public static function update(
        Banner               $banner,
        array                $data,
        Authenticatable|User $user
    ): Banner {
        //
        if (!key_exists('is_active', $data) || !$data['is_active']) {
            $data['is_active'] = false;
        }

        if (!key_exists('button_url', $data)) {
            $data['button_url'] = null;
        }

        //
        $data['created_user_id'] = $user->id;

//        if ($data['type'] === BannerTypes::static) {
//            $data['delay_seconds'] = 0;
//            $data['not_disturb_hours'] = 0;
//        } else {
//            $data['button_text'] = null;
//            $data['button_url'] = null;
//        }

        //
        $banner
            ->fill($data)
            ->setStart(new DateTimeImmutable($data['start']))
            ->setEnd($data['end'] ? new DateTimeImmutable($data['end']) : null)
            ->updatePicture($data['picture'] ?? null)
            ->save();

        return $banner;
    }

    /**
     * @param Banner $banner
     * @param Authenticatable|User $user
     * @return void
     */
    public static function delete(Banner $banner, Authenticatable|User $user): void
    {
        if (!$user->isAdmin()) {
            throw new DomainException('Недостаточно прав доступа.');
        }

        $banner->delete();
    }


}
