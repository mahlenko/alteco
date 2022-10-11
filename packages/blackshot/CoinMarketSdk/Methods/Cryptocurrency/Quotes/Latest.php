<?php

namespace Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Quotes;

/*
 | Метод получения цен токенов
 |
*/
class Latest extends \Blackshot\CoinMarketSdk\Methods\Method
{
    public string $id;
    public string $slug;
    public string $symbol;
    public string $convert = 'USD';
    public string $convert_id;
    public string $aux = 'num_market_pairs,cmc_rank,date_added,tags,platform,max_supply,circulating_supply,total_supply,market_cap_by_total_supply,volume_24h_reported,volume_7d,volume_7d_reported,volume_30d,volume_30d_reported,is_active,is_fiat';
    public string $skip_invalid = 'true';
}
