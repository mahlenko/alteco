<?php

namespace Blackshot\CoinMarketSdk\Models;

class UserSetting extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'value'
    ];
}
