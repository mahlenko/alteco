<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;

class PortfolioDeleteAction
{
    /**
     * @throws PortfolioException
     */
    public static function handle(User $user, int $id): ?bool
    {
        $portfolio = Portfolio::find($id);

        if (!$portfolio) {
            throw new PortfolioException('Портфолио уже нет.');
        }

        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new PortfolioException('Вы не можете удалить это портфолио.');
        }

        return $portfolio->delete();
    }
}
