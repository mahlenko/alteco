<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use App\Models\User;
use Blackshot\CoinMarketSdk\Models\UserSetting;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserSettingsRepository
{
    /**
     * @param string $name
     * @return null
     */
    public static function get(string $name)
    {
        $setting = self::getUser()
            ->settings->where('name', $name)
            ->first();

        if (!$setting) return null;

        $value = json_decode($setting->value);
        if (!json_last_error()) {
            $setting->value = $value;
        }

        return !empty($setting->value) ? $setting->value : null;
    }

    /**
     * @param string $name
     * @param string|null $value
     * @return UserSetting
     */
    public static function save(string $name, string $value = null): UserSetting
    {
        /* @var User $user */
        $user = self::getUser();

        if (empty($value)) {
            return self::empty($name);
        }

        if ($setting = $user->settings->where('name', $name)->first()) {
            $setting->value = $value;
        } else {
            /* Create new setting user */
            $setting = new UserSetting([
                'name' => $name,
                'value' => $value
            ]);

            $setting->user_id = $user->id;
        }

        $setting->save();

        return $setting;
    }

    /**
     * @param string $name
     * @param array $value
     * @return UserSetting
     */
    public static function saveJson(string $name, array $value = []): UserSetting
    {
        if (!$value) {
            return self::empty($name);
        }

        return self::save($name, json_encode($value));
    }

    /**
     * @param string $name
     * @return UserSetting|null
     */
    public static function empty(string $name): ?UserSetting
    {
        $user = self::getUser();

        if ($setting = $user->settings->where('name', $name)->first()) {
            $setting->value = '';
            $setting->save();

            return $setting;
        }

        return null;
    }

    /**
     * @return Authenticatable|null
     */
    private static function getUser(): ?Authenticatable
    {
        return Auth::user();
    }

}
