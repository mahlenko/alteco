<?php

namespace Blackshot\CoinMarketSdk\Endpoints;

class Map implements EndpointInterface
{

    public function __toString(): string
    {
        return '/map';
    }
}
