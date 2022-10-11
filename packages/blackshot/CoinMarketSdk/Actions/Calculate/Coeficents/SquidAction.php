<?php

namespace Blackshot\CoinMarketSdk\Actions\Calculate\Coeficents;

use Blackshot\CoinMarketSdk\Actions\Calculate\DrawdownAction;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\QuoteRepository;
use Illuminate\Support\Collection;

/**
 * Расчет коэффициента кальмара
 */
class SquidAction
{
    /**
     * @param Coin $coin
     * @param Collection|null $priceCollection
     * @return float|null
     */
    public static function handle(Coin $coin, Collection $priceCollection = null): ?float
    {
        if (!$priceCollection) {
            $priceCollection = QuoteRepository::price($coin);
        }

        $profit = $priceCollection->pluck('price');

        $prices = [];
        foreach ($profit as $index => $price) {
            if ($index % 2 === 0) continue;
            $prices[] = round($price / $profit[$index - 1], 8);
        }

        $average_sqrt = sqrt(array_product($prices));
        $drawdown = DrawdownAction::handle($profit);

        if ($average_sqrt == 0 || $drawdown == 0) return 0;

        return round($average_sqrt / $drawdown, 2);
    }
}
