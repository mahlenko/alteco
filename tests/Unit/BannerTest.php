<?php

namespace Tests\Unit;

use Blackshot\CoinMarketSdk\Models\Banner;
use PHPUnit\Framework\TestCase;

class BannerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_set_delay()
    {
        $banner = new Banner();

        $banner->setDelay($delay = 10);
        $this->assertEquals($delay, $banner->delay);
    }
}
