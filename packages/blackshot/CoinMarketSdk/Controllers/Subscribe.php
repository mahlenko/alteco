<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use App\Http\Controllers\Controller;

class Subscribe extends Controller
{
    public function index()
    {
        return view('blackshot::subscribe');
    }
}
