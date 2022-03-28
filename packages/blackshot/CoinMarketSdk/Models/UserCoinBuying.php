<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoinBuying extends Model
{
    protected $table = 'user_coin_buying';

    protected $fillable = [
        'user_id',
        'coin_uuid'
    ];
}
