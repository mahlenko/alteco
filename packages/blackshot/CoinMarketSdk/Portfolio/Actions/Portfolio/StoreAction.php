<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use InvalidArgumentException;
use RuntimeException;

class StoreAction
{
    /**
     * @param User $user
     * @param string $name
     * @param Portfolio|null $portfolio
     * @return Portfolio
     */
    public static function handle(User $user, string $name, Portfolio $portfolio = null): Portfolio
    {
        if (empty(trim($name))) {
            throw new InvalidArgumentException('Имя не может быть пустым.');
        }

        if (!$portfolio) {
            $max_portfolios = config('portfolio.max_portfolios');
            if ($user->portfolios()->count() >= $max_portfolios) {
                throw new RuntimeException('Максимальное количество имеющихся портфолио '. $max_portfolios .'.', 603);
            }

            // Create new portfolio
            return $user->portfolios()->create([
                'name' => $name
            ]);
        }

        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new RuntimeException('Доступ запрещен.', 403);
        }

        $portfolio->update(['name' => trim($name)]);

        return $portfolio;
    }
}
