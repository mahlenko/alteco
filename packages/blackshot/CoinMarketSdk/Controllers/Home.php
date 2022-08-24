<?php

namespace Blackshot\CoinMarketSdk\Controllers;

use Blackshot\CoinMarketSdk\Models\TariffModel;
use Illuminate\Support\Collection;

class Home extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('blackshot::website', [
            'tariffs' => self::tariffs()
        ]);
    }

    /**
     * @return Collection<TariffModel>
     */
    public static function tariffs(): Collection
    {
        return TariffModel::where('free', true)
            ->orWhere('amount', '>', 0)
            ->get()
            ->sortBy('amount');
    }
}
