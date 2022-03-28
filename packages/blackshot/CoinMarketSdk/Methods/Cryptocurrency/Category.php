<?php

namespace Blackshot\CoinMarketSdk\Methods\Cryptocurrency;

class Category extends \Blackshot\CoinMarketSdk\Methods\Method
{
    public string $id;
    public int $start = 1;
    public int $limit = 1000;
    public string $convert;
    public string $convert_id;
}
