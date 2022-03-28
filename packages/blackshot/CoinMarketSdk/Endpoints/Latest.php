<?php

namespace Blackshot\CoinMarketSdk\Endpoints;

class Latest implements EndpointInterface
{

    public function __toString(): string
    {
        return '/latest';
    }
}
