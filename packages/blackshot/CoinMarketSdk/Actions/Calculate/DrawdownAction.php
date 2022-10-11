<?php

namespace Blackshot\CoinMarketSdk\Actions\Calculate;

use Illuminate\Support\Collection;

/**
 * Максимальная просадка
 */
class DrawdownAction
{
    /**
     * Доходность инвестиций
     * @param Collection $collection
     * @return float
     */
    public static function handle(Collection|array $collection): float
    {
        if ($collection instanceof Collection) {
            $collection = $collection->toArray();
        }

        $max_drawdown = 0.0;

        foreach ($collection as $index => $current_profit) {
            if (!$index) continue;

            if ($current_profit < $collection[$index - 1]) {
                $drawdown = round($current_profit / $collection[$index - 1], 5);
                if ($drawdown > $max_drawdown) {
                    $max_drawdown = $drawdown;
                }
            }
        }

        return $max_drawdown;
    }
}
