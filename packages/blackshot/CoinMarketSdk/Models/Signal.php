<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signal extends Model
{
    use HasFactory;

    protected $fillable = [
        'rank',
        'diff',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }

}
