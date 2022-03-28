<?php

namespace Blackshot\CoinMarketSdk\Models;

use DateTimeImmutable;
use Exception;

class Quote extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'coin_quotes';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'uuid', 'coin_uuid', 'currency', 'cmc_rank', 'max_supply',
        'circulating_supply', 'total_supply', 'price',
        'volume_24h', 'volume_24h_reported', 'volume_7d',
        'volume_7d_reported', 'volume_30d', 'volume_30d_reported', 'volume_change_24h',
        'percent_change_1h', 'percent_change_24h', 'percent_change_7d',
        'percent_change_30d', 'percent_change_60d', 'percent_change_90d',
        'market_cap', 'market_cap_dominance', 'fully_diluted_market_cap',
        'market_cap_by_total_supply', 'last_updated'
    ];

    /**
     * @param $query
     * @param DateTimeImmutable $from
     * @param DateTimeImmutable|null $to
     * @return mixed
     * @throws Exception
     */
    public function scopeBetween($query, DateTimeImmutable $from, DateTimeImmutable $to = null)
    {
        if (!$to) $to = new DateTimeImmutable($from->format('Y-m-d 23:59:59'));

        $from = $from->setTime(0, 0);
        $to = $to->setTime(23, 59, 59);

        return $query->whereBetween('last_updated', [
            $from->format('Y-m-d H:i:s'),
            $to->format('Y-m-d H:i:s')
        ]);
    }
}
