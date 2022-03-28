<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use App\Providers\RouteServiceProvider;

class Home extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return \Illuminate\Support\Facades\Auth::check()
            ? redirect(RouteServiceProvider::HOME)
            : redirect()->route('login');
    }
}
