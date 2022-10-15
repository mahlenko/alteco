<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;

class PortfolioUpdateAction
{
    /**
     * @param User $user
     * @param Portfolio $portfolio
     * @param array $data
     * @return Portfolio
     * @throws PortfolioException
     */
    public static function handle(User $user, Portfolio $portfolio, array $data): Portfolio
    {
        if ($user->getKey() !== $portfolio->user_id && !$user->isAdmin()) {
            throw new PortfolioException('Вы не можете изменить портфель для пользователя.');
        }

        if (empty(trim($data['name']))) {
            throw new PortfolioException('Имя не может быть пустым.');
        }

        $portfolio->fill($data);
        $portfolio->save();

        return $portfolio;
    }
}
