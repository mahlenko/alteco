<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\TransactionException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;

class TransactionDeleteAction
{
    /**
     * @throws TransactionException
     */
    public static function handle(User $user, Transaction $transaction): ?bool
    {
        if ($user->getKey() != $transaction->user_id && !$user->isAdmin()) {
            throw new TransactionException('Вы не можете удалить эту транзакцию.');
        }

        return $transaction->delete();
    }
}
