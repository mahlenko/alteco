<?php

namespace Blackshot\CoinMarketSdk\Controllers\Tariffs;

use Blackshot\CoinMarketSdk\Models\TariffModel;

class Home extends \App\Http\Controllers\Controller
{
    public function index()
    {
        $tariffs = TariffModel::withCount(['subscribes'])->paginate();

        return view('blackshot::tariff.index', [
            'tariffs' => $tariffs
        ]);
    }
}
