<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackingCoin extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string
     */
    protected $table = 'tracking_coins';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'coin_uuid'
    ];

    /**
     * @return BelongsTo
     */
    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
