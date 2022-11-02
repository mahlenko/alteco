<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Enums;

use Blackshot\CoinMarketSdk\Enums\EnumTrait;

enum PeriodEnum: string
{
    use EnumTrait;

    case hours24 = '24ч';
    case days7 = '7д';
    case days30 = '30д';
    case days90 = '90d';
    case all = 'Все';
}
