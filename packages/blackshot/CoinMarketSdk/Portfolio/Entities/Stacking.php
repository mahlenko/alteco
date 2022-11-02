<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Entities;

use DateTimeImmutable;
use Illuminate\Support\Collection;

class Stacking extends Collection
{
    public function quantity()
    {
        return $this->sum('amount');
    }

    /**
     * Заработано за все время стейкинга
     * @return float
     */
    public function profitQuantity(): float
    {
        $start_date = $this->min('stacking_at');
        if (!$start_date) return 0;

        $stacking_days = (new DateTimeImmutable())->diff($start_date)->days ?? 1;

        return $this->profitDay() * $stacking_days;
    }

    /**
     * Прибыль от стейкинга в день по монете
     * @return float|int
     */
    public function profitDay()
    {
        $profit = 0;

        foreach ($this as $stacking) {
            $profit += $stacking->amount * ($stacking->apy / 100) / 365;
        }

        return $profit;
    }
}
