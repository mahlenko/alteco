<?php

namespace Blackshot\CoinMarketSdk\Controllers;

class OfferController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('blackshot::offer');
    }
}
