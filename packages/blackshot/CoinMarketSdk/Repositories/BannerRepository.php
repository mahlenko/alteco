<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use App\Models\User;
use Blackshot\CoinMarketSdk\Models\TariffBanner;
use DateTimeImmutable;
use DomainException;
use Exception;
use Illuminate\Contracts\Auth\Authenticatable;

class BannerRepository
{

    /**
     * @param array $data
     * @param Authenticatable|User $user
     * @return TariffBanner
     * @throws Exception
     */
    public static function store(array $data, Authenticatable|User $user): TariffBanner
    {
        if (key_exists('uuid', $data) && $data['uuid']) {
            $banner = TariffBanner::find($data['uuid']);
        } else {
            $banner = new TariffBanner();
            $banner->tariff_id = $data['tariff_id'];
        }

        return self::update($banner, $data, $user);
    }

    /**
     * @param TariffBanner $banner
     * @param array $data
     * @param Authenticatable|User $user
     * @return TariffBanner
     * @throws Exception
     */
    public static function update(
        TariffBanner $banner,
        array $data,
        Authenticatable|User $user
    ): TariffBanner {
        //
        if (!key_exists('is_active', $data) || !$data['is_active']) {
            $data['is_active'] = false;
        }

        //
        $data['created_user_id'] = $user->id;

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
     * @param TariffBanner $banner
     * @param Authenticatable|User $user
     * @return void
     */
    public static function delete(TariffBanner $banner, Authenticatable|User $user): void
    {
        if (!$user->isAdmin()) {
            throw new DomainException('Недостаточно прав доступа.');
        }

        $banner->delete();
    }


}
