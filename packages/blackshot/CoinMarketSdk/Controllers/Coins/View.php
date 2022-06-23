<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Illuminate\Support\Facades\Cache;

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

        return view('blackshot::coins.view', [
            'coin' => $coin,
            'charts' => $signals->pluck('rank', 'date')
        ]);
    }
}
