<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use RuntimeException;

class DeleteAction
{
    public static function handle(User $user, int $id): ?bool
    {
        $portfolio = Portfolio::find($id);

        if (!$portfolio) {
            throw new RuntimeException('Портфолио уже нет.', 404);
        }

        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new RuntimeException('Вы не можете удалить это портфолио.', 500);
        }

        return $portfolio->delete();
    }
}
