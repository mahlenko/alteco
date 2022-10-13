<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFavorites extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_favorites';

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'coin_uuid'];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class, 'coin_uuid', 'uuid');
    }
}
