<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use Blackshot\CoinMarketSdk\Helpers\NumberHelper;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class View extends \App\Http\Controllers\Controller
{
    /**
     * @param string $uuid
     * @return \Illuminate\Contracts\View\View
     */
    public function index(string $uuid): \Illuminate\Contracts\View\View
    {
        $coin = Coin::where('uuid', $uuid)->firstOrFail();

        $signals = Cache::rememberForever('signals:'. $coin->uuid, function() use ($coin) {
            return Signal::select(['rank', 'date'])
                ->where('coin_uuid', $coin->uuid)
                ->where('date', '>=', new DateTimeImmutable('-1 year'))
                ->get();
        });

        $prices = [];
        foreach($coin->quotes as $quote) {
            $price = Str::replace(' ', '', NumberHelper::format($quote->price));
//            $prices[$quote->last_updated->format('Y-m-d')] = floatval($price);
            $prices[$quote->last_updated->format('Y-m-d')] = $price;
        }

        return view('blackshot::coins.view', [
            'coin' => $coin,
            'charts' => [
                'rang' => $signals->pluck('rank', 'date'),
                'prices' => $prices
            ],
        ]);
    }
}
