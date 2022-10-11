<?php

namespace Blackshot\CoinMarketSdk\Methods;

abstract class Method
{
    const VERSION = 'v1';

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach (get_class_vars(get_class($this)) as $key => $value)
        {
            if (key_exists($key, $data)) {
                $this->$key = $data[$key];
            }
        }
    }
}
