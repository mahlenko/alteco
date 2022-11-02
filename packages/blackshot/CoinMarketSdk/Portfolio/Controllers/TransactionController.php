<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use Blackshot\CoinMarketSdk\Controller;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Portfolio $portfolio, Coin $coin)
    {
        return view('portfolio::transactions.list', [
            'coin' => $coin,
            'transactions' => $portfolio->items()->findCoin($coin)->transactions->sortByDesc('date_at')
        ]);
    }

    /**
     * Страница добавления актива
     * @param Portfolio $portfolio
     * @return View
     */
    public function create(Portfolio $portfolio): View
    {
        $coins = CoinRepository::handle(with: ['info']);

        return view('portfolio::transactions.create', [
            'portfolio' => $portfolio,
            'coins' => $coins->sortBy('rank'),
            'trans' => [
                'transaction' => [
                    TransactionTypeEnum::Buy->name => 'Покупка',
                    TransactionTypeEnum::Sell->name => 'Продажа',
                    TransactionTypeEnum::Transfer->name => 'Перевод',
                ]
            ]
        ]);
    }
}
