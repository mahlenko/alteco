<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Enums;

use Blackshot\CoinMarketSdk\Enums\EnumTrait;

enum CurrencyEnum: string
{
    use EnumTrait;

    case USD = 'USD';
    case BTC = 'BTC';
}
