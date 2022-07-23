<?php

namespace Blackshot\CoinMarketSdk\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Тарифы
 */
class TariffModel extends Model
{
    protected $table = 'tariffs';

    protected $fillable = [
        'name',
        'amount',
        'days',
        'free',
        'default',
        'description',
        'payment_widget',
    ];

    public function isFree(): bool
    {
        return (bool) $this->free;
    }

    public function isDefault(): bool
    {
        return (bool) $this->default;
    }

    public function subscribes(): HasMany
    {
        return $this->hasMany(User::class, 'tariff_id', 'id');
    }
}
