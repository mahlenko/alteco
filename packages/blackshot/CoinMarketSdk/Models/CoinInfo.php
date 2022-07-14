<?php

namespace Blackshot\CoinMarketSdk\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class CoinInfo extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'coin_info';
    protected $primaryKey = 'coin_uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'coin_uuid', 'category', 'logo', 'description', 'notice', 'date_added'
    ];

    /**
     * @return HasMany
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tags::class, 'coin_uuid', 'coin_uuid');
    }

    /**
     * @return HasMany
     */
    public function urls(): HasMany
    {
        return $this->hasMany(CoinUrl::class, 'coin_uuid', 'coin_uuid');
    }

    /**
     * @param string $type
     * @param string $url
     * @return void
     */
    public function attachUrl(string $type, string $url)
    {
        $is_unique = !(bool) CoinUrl::where([
            'coin_uuid' => $this->coin_uuid,
            'type' => $type,
            'url' => $url
        ])->count();

        if (!$is_unique) return;

        return CoinUrl::create([
            'coin_uuid' => $this->coin_uuid,
            'type' => $type,
            'url' => $url
        ]);
    }

    /**
     * @param string $name
     * @return void
     */
    public function attachTag(string $name)
    {
        $name = trim($name);

        $is_unique = !(bool) Tags::where([
            'coin_uuid' => $this->coin_uuid,
            'name' => $name
        ])->count();

        if (!$is_unique || empty($name)) return;

        return Tags::create([
            'coin_uuid' => $this->coin_uuid,
            'name' => $name
        ]);
    }
}
