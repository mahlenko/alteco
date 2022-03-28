<?php

namespace Blackshot\CoinMarketSdk\Methods\Cryptocurrency;

class Categories extends \Blackshot\CoinMarketSdk\Methods\Method
{
    public int $start = 1;
    public int $limit = 1000;
    public string $id;
    public string $slug;
    public string $symbol;
}
