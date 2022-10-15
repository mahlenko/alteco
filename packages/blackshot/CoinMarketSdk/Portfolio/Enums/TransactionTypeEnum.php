<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Enums;

/**
 * Тип активов
 */
enum TransactionTypeEnum
{
    case Buy;
    case Sell;
    case Transfer;
}
