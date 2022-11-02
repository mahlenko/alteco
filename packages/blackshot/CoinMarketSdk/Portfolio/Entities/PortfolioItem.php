<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Entities;

use Blackshot\CoinMarketSdk\Models\Coin;
use Illuminate\Support\Collection;

class PortfolioItem
{
    public readonly float $ratio;
    public readonly Stacking $stacking;

    public function __construct(
        public readonly int $portfolio_id,
        public readonly Coin $coin,
        public readonly Collection $transactions,
        Collection $staking = null
    ) {
        $this->stacking = new Stacking($staking);
    }

    /**
     * Цена монеты сейчас
     * @return float
     */
    public function coinPrice(): float
    {
        return $this->coin->price;
    }

    /**
     * Сумма стоимости покупки
     * @return float
     */
    public function buyPrice(): float
    {
        return $this->transactions->sum('total');
    }

    /**
     * Текущая стоимость моих монет
     * @return float
     */
    public function currentPrice(): float
    {
        return $this->quantity() * $this->coinPrice();
    }

    /**
     * Общее количество монеты
     * @return float
     */
    public function quantity(): float
    {
        return $this->transactions->sum('quantity');
    }

    /**
     * Средняя стоимость покупки
     * @return float
     */
    public function priceBuyAverage(): float
    {
        return $this->transactions->average('price');
    }

    /**
     * Прибыль/убыток монеты
     * @return float
     */
    public function profitPrice(): float
    {
        return $this->currentPrice() - $this->buyPrice();
    }

    /**
     * Прибыль/убыток монеты в процентах
     * @return float
     */
    public function profitPercent(): float
    {
        return $this->profitPrice() / $this->buyPrice() * 100;
    }

    public function setRatio(float $ratio): void
    {
        $this->ratio = round($ratio, 2);
    }
}
