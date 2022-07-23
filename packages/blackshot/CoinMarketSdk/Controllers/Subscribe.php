<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\TariffModel;

class Subscribe extends Controller
{
    public function index()
    {
        return view('blackshot::subscribe', [
            'tariffs' => TariffModel::where('default', false)->orderByDesc('amount')->get()
        ]);
    }
}
