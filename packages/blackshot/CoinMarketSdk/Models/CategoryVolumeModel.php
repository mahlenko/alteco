<?php

namespace Blackshot\CoinMarketSdk\Models;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CategoryVolumeModel extends Model
{
    protected $table = 'category_volumes';
    protected $primaryKey = 'category_uuid';
    protected $keyType = 'string';

    protected $fillable = ['category_uuid', 'volume', 'volume_change'];

    /**
     * @return BelongsTo
     */
    protected function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'uuid', 'category_uuid');
    }
}
