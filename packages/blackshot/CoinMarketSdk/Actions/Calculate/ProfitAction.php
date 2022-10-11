<?php

namespace Blackshot\CoinMarketSdk\Actions\Calculate;

use Illuminate\Support\Collection;

class ProfitAction
{
    /**
     * Доходность инвестиций
     * @param Collection $collection
     * @param string $key
     * @return array
     */
    public static function handle(Collection $collection, string $key = 'price'): array
    {
        $result = [];

        foreach ($collection as $index => $item) {
            if ($index % 2 != 0) {
                $item = (array) $item;
                $previous = (array) $collection[$index - 1];

                $result[] = self::profit($previous[$key], $item[$key]);
            }
        }

        return $result;
    }

    /**
     * Расчет профита в процентах
     * @param float $buy
     * @param float $current
     * @return float
     * @see https://journal.tinkoff.ru/indicators/?ysclid=l5fy2ow5sm924408326
     */
    private static function profit(float $buy, float $current): float
    {
        $profit = ($current - $buy) / $buy;
        return $profit * 100;
    }
}
