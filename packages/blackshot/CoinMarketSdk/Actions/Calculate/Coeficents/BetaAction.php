<?php

namespace Blackshot\CoinMarketSdk\Actions\Calculate\Coeficents;

use Blackshot\CoinMarketSdk\Actions\Calculate\ProfitAction;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\QuoteRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BetaAction
{
    /**
     * @param Coin $coin
     * @param array $crixProfit
     * @param Collection|null $quotes
     * @return array|null
     */
    public static function handle(Coin $coin, array $crixProfit, Collection $quotes = null): ?array
    {
        if (!function_exists('trader_beta')) {
            throw new RuntimeException('Run `pecl install trader` PHP extension.');
        }

        if (!$quotes) $quotes = QuoteRepository::price($coin);

        $profit = ProfitAction::handle($quotes);
        if (!$profit) return null;

        $result = trader_beta($profit, $crixProfit, 2);

        return is_array($result) ? array_values($result) : null;
    }
}
