<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;


use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\TrackingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tracking extends \App\Http\Controllers\Controller
{
    /**
     * @param Request $request
     * @return array
     */
    public function index(Request $request): array
    {
        $data = $request->validate([
            'uuid' => ['required', 'exists:coins']
        ]);

        $coin = Coin::find($data['uuid']);
        if (!$coin) {
            return [
                'ok' => false,
                'description' => 'Coin not found.'
            ];
        }

        return [
            'ok' => true,
            'data' => [
                'tracking' => TrackingRepository::toggle($coin, Auth::user())
            ]
        ];
    }
}
