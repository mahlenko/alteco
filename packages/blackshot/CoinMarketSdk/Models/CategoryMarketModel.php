<?php

namespace Blackshot\CoinMarketSdk\Models;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryMarketModel extends Model
{
    protected $table = 'category_markets';
    protected $primaryKey = 'category_uuid';
    protected $keyType = 'string';

    protected $fillable = ['category_uuid', 'cap', 'cap_change'];

    /**
     * @return BelongsTo
     */
    protected function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'uuid', 'category_uuid');
    }
}
