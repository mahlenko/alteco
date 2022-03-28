<?php

namespace Blackshot\CoinMarketSdk\Controllers\Users;


use Blackshot\CoinMarketSdk\Repositories\FavoritesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Favorite extends \App\Http\Controllers\Controller
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

        return [
            'ok' => true,
            'data' => [
                'favorite' => FavoritesRepository::favorite($data['uuid'], Auth::id())
            ]
        ];
    }
}
