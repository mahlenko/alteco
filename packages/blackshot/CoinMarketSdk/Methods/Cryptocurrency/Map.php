<?php

namespace Blackshot\CoinMarketSdk\Methods\Cryptocurrency;

class Map extends \Blackshot\CoinMarketSdk\Methods\Method
{
    public string $listing_status = 'active';
    public int $start = 1;
    public int $limit = 100; // 2000
    public string $sort = 'cmc_rank';
    public string $symbol;
    public string $aux = 'platform,first_historical_data,last_historical_data,is_active,status';
}
