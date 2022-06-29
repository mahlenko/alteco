<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use App\Providers\RouteServiceProvider;

class Home extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('blackshot::website');
    }
}
