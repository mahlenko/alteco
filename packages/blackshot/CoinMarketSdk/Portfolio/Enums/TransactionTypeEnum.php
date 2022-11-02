<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Enums;

use Blackshot\CoinMarketSdk\Enums\EnumTrait;
use RuntimeException;

/**
 * Тип активов
 */
enum TransactionTypeEnum
{
    use EnumTrait;

    case Buy;
    case Sell;
    case Transfer;
}
