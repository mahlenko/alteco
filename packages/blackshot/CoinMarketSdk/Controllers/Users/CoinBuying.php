<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoinBuying extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'uuid' => ['required', 'uuid', 'exists:Blackshot\CoinMarketSdk\Models\Coin,uuid']
        ]);

        return [
            'ok' => UserRepository::toggleBuyingCoin(
                        User::find(Auth::id()),
                        Coin::find($data['uuid'])
                    ),
        ];
    }
}
