<?php

namespace Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Listings;

class Latest extends \Blackshot\CoinMarketSdk\Methods\Method
{
    public int $start = 1;
    public int $limit = 100;
    public int $price_min;
    public int $price_max;
    public int $market_cap_min;
    public int $market_cap_max;
    public int $volume_24h_min;
    public int $volume_24h_max;
    public int $circulating_supply_min;
    public int $circulating_supply_max;
    public int $percent_change_24h_min;
    public int $percent_change_24h_max;
    public string $convert = 'USD';
    public string $convert_id;
    public string $sort = 'market_cap';
    public string $sort_dir = 'asc';
    public string $cryptocurrency_type = 'all';
    public string $tag = 'all';
    public string $aux = 'num_market_pairs,cmc_rank,date_added,tags,platform,max_supply,circulating_supply,total_supply';
}
