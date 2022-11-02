<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Enums;

use Blackshot\CoinMarketSdk\Enums\EnumTrait;

enum TransferTypeEnum
{
    use EnumTrait;

    case In;
    case Out;
}
