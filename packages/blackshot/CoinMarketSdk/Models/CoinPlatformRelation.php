<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinPlatformRelation extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'coin_platform_relations';
    protected $fillable = ['coin_uuid', 'platform_uuid'];

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
    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class);
    }
}
