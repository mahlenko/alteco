<?php

namespace Blackshot\CoinMarketSdk\Controllers\Banner;

use Blackshot\CoinMarketSdk\Models\Banner;

class Home extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('blackshot::banners.index', [
            'banners' => Banner::paginate()
        ]);
    }
}
