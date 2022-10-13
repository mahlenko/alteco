<?php

namespace Blackshot\CoinMarketSdk\Repositories;

use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Models\UserPaymentsModel;

/**
 * Тарифы
 */
class PaymentRepository
{
    /**
     * @param User $user
     * @param float $amount
     * @param string|null $type
     * @param string|null $description
     * @return UserPaymentsModel
     */
    public static function create(
        User $user,
        float $amount,
        string $type = null,
        string $description = null): UserPaymentsModel
    {
        return UserPaymentsModel::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => $type,
            'description' => $description
        ]);
    }

    /**
     * Оплата тарифа (подписки)
     * @param User $user
     * @param TariffModel $tariffModel
     * @return UserPaymentsModel
     */
    public static function payTariff(
        User $user,
        TariffModel $tariffModel
    ): UserPaymentsModel
    {
        return self::create(
            $user,
            $tariffModel->amount,
            UserPaymentsModel::TYPE_TARIFF,
            'Оплата тарифа "'. $tariffModel->name .'"'
        );
    }
}
