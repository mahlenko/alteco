<?php

namespace Blackshot\CoinMarketSdk\Endpoints;

class Info implements EndpointInterface
{

    public function __toString(): string
    {
        return '/info';
    }
}
