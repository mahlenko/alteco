<?php

namespace Blackshot\CoinMarketSdk\Enums;

use RuntimeException;

trait EnumTrait
{
    public static function byName(string $value)
    {
        foreach (self::cases() as $case) {
            if ($case->name == $value) {
                return $case;
            }
        }

        return false;
    }

    public static function tryFrom($value): bool
    {
        foreach (self::cases() as $case) {
            if ($case->name == $value) return true;
        }

        throw new RuntimeException('The value '. $value .' is invalid.');
    }
}
