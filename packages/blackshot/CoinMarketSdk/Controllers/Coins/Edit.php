<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\Coin;

class Edit extends Controller
{
    public function index(Coin $coin)
    {
        return view('blackshot::coins.edit', [
            'coin' => $coin
        ]);
    }
}
