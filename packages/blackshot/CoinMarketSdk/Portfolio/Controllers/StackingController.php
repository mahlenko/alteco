<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use Blackshot\CoinMarketSdk\Controller;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\View\View;

class StackingController extends Controller
{
    public function index(Portfolio $portfolio, Coin $coin)
    {
        return view('portfolio::stacking.list', [
            'coin' => $coin,
            'stacking' => $portfolio->items()->findCoin($coin)->stacking->sortDesc()
        ]);
    }

    /**
     * Страница добавления актива
     * @param Portfolio $portfolio
     * @param Coin $coin
     * @return View
     */
    public function create(Portfolio $portfolio, Coin $coin): View
    {
        return view('portfolio::stacking.create', [
            'portfolio' => $portfolio,
            'portfolio_coin' => $portfolio->items()->findCoin($coin),
            'coin' => $coin
        ]);
    }
}
