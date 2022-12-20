<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use Auth;
use Blackshot\CoinMarketSdk\Controller;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\api\ApiChartsController;
use Blackshot\CoinMarketSdk\Portfolio\Enums\PeriodEnum;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class PortfolioController extends Controller
{
    public function index(int $portfolio_id = null): View|RedirectResponse
    {
        $portfolios = Auth::user()->portfolios;

        if (isset($portfolio_id) && $portfolio_id) {
            if ($portfolio_id == $portfolios->first()->getKey()) {
                return redirect()->route('portfolio.home');
            }

            $portfolio = $portfolios->find($portfolio_id);
            if (!$portfolio) abort(404);
        } else {
            $portfolio = $portfolios->first();
        }

        // Изменение за 24 часа
        // todo: сделать в портфеле эту функцию
        $changePrice24 = 0;
        if($portfolio) {
            $changePrice24Data = $portfolio->items()->chartData(PeriodEnum::hours24, CurrencyEnum::USD);
            if ($changePrice24Data && $changePrice24Data->count()) {
                $changePrice24 = $changePrice24Data->last()['value'] - $changePrice24Data->first()['value'];
            }
        }
        return view('portfolio::layout', [
            'portfolio' => $portfolio,
            'portfolios' => $portfolios,
            'totalPrice' => self::totalPortfolioPrice($portfolios),
            'profit' => self::profitStacking($portfolios),
            'changePrice24' => $changePrice24
        ]);
    }

    public function create()
    {
        return view('portfolio::portfolio.create');
    }

    private static function totalPortfolioPrice(Collection $portfolios): float
    {
        $price = 0;
        foreach ($portfolios as $item) {
            $price += $item->items()->currentPrice();
        }

        return $price;
    }

    private static function profitStacking(Collection $portfolios): array
    {
        $result = [
            'month' => 0,
            'year' => 0
        ];

        if ($portfolios->count()) {
            foreach ($portfolios as $portfolio) {
                $result['month'] += $portfolio->items()->profitStacking(30);
                $result['year'] += $portfolio->items()->profitStacking(365);
            }
        }

        return $result;
    }
}
