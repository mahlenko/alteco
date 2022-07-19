<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoinInfo extends Model
{
    protected $table = 'coin_info';
    protected $primaryKey = 'coin_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'coin_uuid', 'category', 'logo', 'description', 'notice', 'date_added'
    ];

    protected $casts = [
        'date_added' => 'datetime'
    ];

    /**
     * @return HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tags::class, 'coin_uuid', 'coin_uuid');
    }

    /**
     * @return HasMany
     */
    public function urls(): HasMany
    {
        return $this->hasMany(CoinUrl::class, 'coin_uuid', 'coin_uuid');
    }
}
