<?php

namespace Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Ohlcv;

use Blackshot\CoinMarketSdk\Methods\Method;

class Latest extends Method
{
    public string $id;
    public string $symbol;
    public string $convert = 'USD';
    public string $convert_id;
    public string $skip_invalid = 'true';
}
