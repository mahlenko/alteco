<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use RuntimeException;

class DeleteAction
{
    public static function handle(User $user, Portfolio $portfolio)
    {
        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new RuntimeException('Вы не можете удалить это портфолио.', 500);
        }

        $portfolio->delete();
    }
}
