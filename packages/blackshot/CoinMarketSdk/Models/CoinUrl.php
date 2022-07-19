<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;

class CoinUrl extends Model
{
    protected $table = 'coin_urls';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = ['type', 'url'];
}
