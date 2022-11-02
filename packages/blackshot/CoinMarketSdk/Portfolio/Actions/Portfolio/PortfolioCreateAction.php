<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;

class PortfolioCreateAction
{
    /**
     * @param User $user
     * @param array $data
     * @return Portfolio
     * @throws PortfolioException
     */
    public static function handle(User $user, array $data): Portfolio
    {
        if ($user->getKey() != $data['user_id'] && !$user->isAdmin()) {
            throw new PortfolioException('Вы не можете добавить портфель для пользователя.');
        }

        /* @var User $owner */
        $owner = User::find($data['user_id']);
        if (!$owner) {
            throw new PortfolioException('Пользователь портфеля не найден.');
        }

        if (empty(trim($data['name']))) {
            throw new PortfolioException('Имя не может быть пустым.');
        }

        $max_portfolios = config('portfolio.max_portfolios');
        if ($owner->portfolios()->count() >= $max_portfolios) {
            throw new PortfolioException('Максимальное количество имеющихся портфолио '. $max_portfolios .'.');
        }

        return $owner->portfolios()->create([
            'name' => $data['name']
        ]);
    }
}
