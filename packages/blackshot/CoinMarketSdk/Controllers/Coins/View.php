<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;

class View extends \App\Http\Controllers\Controller
{
    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $uuid): \Illuminate\Contracts\View\View
    {
        $coin = Coin::where('uuid', $uuid)->firstOrFail();

        /*  */
//        $quotes_last_day = (CoinRepository::groupByDate($coin->quotes))->map(function($quotes) {
//            return $quotes->last();
//        })->values();

        $signals = Signal::where([
            'coin_uuid' => $coin->uuid
        ])->get();

        return view('blackshot::coins.view', [
            'coin' => $coin,
//            'charts' => $quotes_last_day->pluck('cmc_rank', 'last_updated')
            'charts' => $signals->pluck('rank', 'date')
        ]);
    }
}
