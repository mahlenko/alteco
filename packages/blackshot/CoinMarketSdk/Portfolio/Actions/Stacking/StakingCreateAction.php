<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Stacking;

use Blackshot\CoinMarketSdk\Helpers\NumberHelper;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Stacking;

class StakingCreateAction
{
    /**
     * @throws PortfolioException
     */
    public static function handle(
        User $user,
        Portfolio $portfolio,
        Coin $coin,
        array $data = []): Stacking
    {
        if ($portfolio->user_id != $user->getKey() && !$user->isAdmin()) {
            throw new PortfolioException('Вы не можете добавить информацию о стейкинге в это портфолио.');
        }

        $portfolio_coin = $portfolio->items()->findCoin($coin);

        /* Монеты нет в портфеле */
        if (!$portfolio_coin) {
            throw new PortfolioException('Вы не можете стейкать монету, которой нет в портфеле.');
        }

        /* Не превышаем количество свободных для стейкинга монет */
        $free_stacking = $portfolio_coin->quantity() - $portfolio_coin->stacking->quantity();
        if ($data['amount'] > $free_stacking) {
            throw new PortfolioException(
                sprintf(
                    'Максимальное количество монет для стейкинга: %s %s',
                    NumberHelper::format($free_stacking),
                    $portfolio_coin->coin->symbol
                )
            );
        }

        return $portfolio->stacking()->create(array_merge($data, [
            'user_id' => $portfolio->user_id,
            'coin_uuid' => $coin->getKey()
        ]));
    }
}
