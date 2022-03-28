<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    protected $fillable = ['id', 'name', 'title', 'num_tokens', 'avg_price_change', 'last_updated'];

    /**
     * @return HasMany
     */
    public function markets(): HasMany
    {
        return $this->hasMany(CategoryMarketModel::class, 'category_uuid', 'uuid');
    }

    /**
     * @return HasMany
     */
    public function volumes(): HasMany
    {
        return $this->hasMany(CategoryVolumeModel::class, 'category_uuid', 'uuid');
    }
}
