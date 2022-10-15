<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\TransactionException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;

class TransactionUpdateAction
{
    /**
     * @throws TransactionException
     */
    public static function handle(User $user, Transaction $transaction, array $data): Transaction
    {
        if ($transaction->user_id != $user->getKey() && !$user->isAdmin()) {
            throw new TransactionException('Вы не можете изменить транзакцию в этом портфолио.');
        }

        $transaction->fill($data);
        $transaction->save();

        return $transaction;
    }
}
