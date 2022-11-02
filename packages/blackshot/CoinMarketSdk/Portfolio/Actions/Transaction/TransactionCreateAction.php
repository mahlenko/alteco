<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\TransactionException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;

class TransactionCreateAction
{
    /**
     * Добавит актив пользователю
     * @param User $user
     * @param Portfolio $portfolio
     * @param Coin $coin
     * @param array $data
     * @return Transaction
     * @throws TransactionException
     */
    public static function handle(User $user, Portfolio $portfolio, Coin $coin, array $data): Transaction
    {
        if (!$portfolio->isUserTo($user) && !$user->isAdmin()) {
            throw new TransactionException('Вы не можете добавить транзакцию в это портфолио.');
        }

        if (floatval($data['price']) <= 0) {
            throw new TransactionException('Укажите стоимость монеты.');
        }

        if (floatval($data['quantity']) <= 0) {
            throw new TransactionException('Укажите количество количество монет.');
        }

        if (floatval($data['fee']) < 0) {
            throw new TransactionException('Плата должна быть больше или равна 0.');
        }

        if (!isset($data['fee'])) $data['fee'] = 0;

        return $portfolio->transactions()->create(array_merge($data, [
            'user_id' => $user->getKey(),
            'coin_uuid' => $coin->uuid
        ]));
    }
}
