<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use InvalidArgumentException;
use RuntimeException;

class CreateAction
{
    const MAX_USER_PORTFOLIO = 2;
    const LIMIT_ERROR = 'Максимальное количество имеющихся портфолио ' . self::MAX_USER_PORTFOLIO.'.';

    /**
     * @param User $user
     * @param string $name
     * @return Portfolio
     */
    public static function handle(User $user, string $name): Portfolio
    {
        if ($user->portfolios()->count() >= self::MAX_USER_PORTFOLIO) {
            throw new RuntimeException(self::LIMIT_ERROR, 613);
        }

        if (empty(trim($name))) {
            throw new InvalidArgumentException('Имя не может быть пустым.');
        }

        return Portfolio::factory()->create([
            'user_id' => $user->id,
            'name' => trim($name)
        ]);
    }
}
