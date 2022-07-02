<?php

namespace Blackshot\CoinMarketSdk\Enums;

enum BannerTypes: string {
    case static = 'Баннер';
    case modal = 'Модальное окно';

    public static function toString(string $name): string
    {
        foreach (self::cases() as $case) {
            if ($case->name == $name) return $case->value;
        }

        return '';
    }
}
