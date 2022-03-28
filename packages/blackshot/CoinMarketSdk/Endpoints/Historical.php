<?php

namespace Blackshot\CoinMarketSdk\Endpoints;

class Historical implements EndpointInterface
{

    public function __toString(): string
    {
        return '/historical';
    }
}
