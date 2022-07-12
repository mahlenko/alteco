<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;

class CrixIndex extends Model
{
    protected $fillable = [
        'date',
        'index'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];
}
