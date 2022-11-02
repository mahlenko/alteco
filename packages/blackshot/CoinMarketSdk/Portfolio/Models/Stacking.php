<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Models;

use Blackshot\CoinMarketSdk\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stacking extends Model
{
    use HasFactory;

    protected $table = 'portfolio_stackings';
    protected $fillable = [
        'user_id',
        'coin_uuid',
        'amount',
        'apy',
        'stacking_at',
    ];
    protected $casts = ['stacking_at' => 'date'];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
