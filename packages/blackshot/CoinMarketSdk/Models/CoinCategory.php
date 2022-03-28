<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoinCategory extends \Illuminate\Database\Eloquent\Model
{
    /**
     * @var string
     */
    protected $table = 'coin_categories';

    /**
     * @return BelongsTo
     */
    public function coin(): BelongsTo
    {
        return $this->belongsTo(Coin::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class);
    }
}
