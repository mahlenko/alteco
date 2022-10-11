<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Helpers\NumberHelper;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Repositories\QuoteRepository;
use Blackshot\CoinMarketSdk\Repositories\SignalRepository;
use DB;

class View extends Controller
{
    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $uuid): \Illuminate\Contracts\View\View
    {
        /*  */
        $coin = CoinRepository::handle()
            ->where('uuid', $uuid)
            ->first();

        if (!$coin) abort(404);

        /*  */
        $quotes = QuoteRepository::price($coin)?->first()
            ->map(function($quote) {
                $quote['price'] = NumberHelper::format($quote['price']);
                return $quote;
            });

        /*  */
        $signals = SignalRepository::handle($coin)?->first();

        return view('blackshot::coins.view', [
            'coin' => $coin,
            'charts' => [
                'rank' => $signals->pluck('rank', 'date'),
                'prices' => $quotes->pluck('price', 'last_updated')
            ],
        ]);
    }
}
