<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Тарифы
 */
class UserPaymentsModel extends Model
{
    const TYPE_TARIFF = 'payment_tariff';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description'
    ];

    public function scopeTariff($query)
    {
        $query->where('type', self::TYPE_TARIFF);
    }
}
