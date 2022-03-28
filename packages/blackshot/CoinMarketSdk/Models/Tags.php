<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    protected $table = 'coin_tags';
    protected $fillable = ['coin_uuid', 'name'];
}
