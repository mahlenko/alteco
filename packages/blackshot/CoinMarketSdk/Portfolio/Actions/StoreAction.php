<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use InvalidArgumentException;
use RuntimeException;

class StoreAction
{
    const MAX_PORTFOLIO = 2;

    const LIMIT_ERROR = 'Максимальное количество имеющихся портфолио ' . self::MAX_PORTFOLIO.'.';

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
            if ($user->portfolios()->count() >= self::MAX_PORTFOLIO) {
                throw new RuntimeException(self::LIMIT_ERROR, 603);
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
