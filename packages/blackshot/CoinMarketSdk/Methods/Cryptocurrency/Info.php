<?php

namespace Blackshot\CoinMarketSdk\Methods\Cryptocurrency;

class Info extends \Blackshot\CoinMarketSdk\Methods\Method
{
    const VERSION = 'v2';

    public string $id;
    public string $slug;
    public string $symbol;
    public string $address;
    public string $aux = 'urls,logo,description,tags,platform,date_added,notice,status';
}
