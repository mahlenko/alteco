<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use InvalidArgumentException;
use RuntimeException;

class UpdateAction
{

    /**
     * @param User $user
     * @param Portfolio $portfolio
     * @param array $data
     * @return Portfolio
     */
    public static function handle(User $user, Portfolio $portfolio, array $data): Portfolio
    {
        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new RuntimeException('Доступ запрещен.', 403);
        }

        if (!key_exists('name', $data) || empty(trim($data['name']))) {
            throw new InvalidArgumentException('Имя не может быть пустым.');
        }

        $portfolio->update(['name' => trim($data['name'])]);

        return $portfolio;
    }
}
