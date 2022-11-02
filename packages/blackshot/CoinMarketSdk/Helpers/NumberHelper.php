<?php

namespace Blackshot\CoinMarketSdk\Helpers;

use Illuminate\Support\Str;

class NumberHelper
{
    public static function format(float $number = null, $decimal_separator = '.', $thousands_separator = ' ')
    {
        if (is_null($number)) return 0;

        $result = number_format($number, self::decimals($number), $decimal_separator, $thousands_separator);

        return strpos($result, '.')
            ? $result
            : number_format($number, 0, $decimal_separator, $thousands_separator);
    }

    public static function decimals(float $number = null): int
    {
        if (is_null($number)) return 0;

        $number_string = number_format($number, 15);

        $decimal_string = Str::substr($number_string, strpos($number_string, '.') + 1);

        $start_zero_count = 0;
        foreach(str_split($decimal_string) as $index => $num) {
            if ($num == '0') $start_zero_count++;
            else break;
        }

        $decimals_result = $start_zero_count + 4;
        if ($number > 0.1) $decimals_result = 2;

        $result = rtrim(number_format($number, $decimals_result), 0);

        return Str::length(Str::substr($result, strpos($result, '.') + 1));
    }
}
