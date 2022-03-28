<?php

namespace Blackshot\CoinMarketSdk;

class Response
{
    public bool $ok = false;
    public array $data;
    public int $code;
    public string $description;

    /**
     * @param $data
     * @return $this
     */
    public function ok($data): self
    {
        $this->ok = true;
        $this->data = (array) $data;
        return $this;
    }

    /**
     * @param $description
     * @param $code
     * @return $this
     */
    public function fail($description, $code): self
    {
        $this->ok = false;
        $this->description = $description;
        $this->code = $code;

        return $this;
    }
}
