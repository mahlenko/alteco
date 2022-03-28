<?php

namespace Blackshot\CoinMarketSdk\Models;

class Platform extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'coin_platforms';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = ['uuid', 'id', 'name', 'symbol', 'slug', 'token_address'];
}
