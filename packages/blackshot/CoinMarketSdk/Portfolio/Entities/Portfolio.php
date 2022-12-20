<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Entities;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\CoinPriceStatisticAction;
use Blackshot\CoinMarketSdk\Portfolio\Enums\PeriodEnum;
use Blackshot\CoinMarketSdk\Portfolio\Enums\CurrencyEnum;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Collection;

class Portfolio extends Collection
{
    /**
     * Общая стоимость покупки
     * @return float
     */
    public function buyPrice(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->buyPrice();
        }

        return $total;
    }

    /**
     * Текущая стоимость портфеля
     * @return float
     */
    public function currentPrice(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->currentPrice();
        }

        return $total;
    }

    /**
     * Прибыль/убытки
     * @return float
     */
    public function profitPrice(): float
    {
        return $this->currentPrice() - $this->buyPrice();
    }

    /**
     * Прибыль/убытки в процентах
     * @return float
     */
    public function profitPercent(): float
    {
        if (!$this->buyPrice()) return 0;
        return $this->profitPrice() / $this->buyPrice() * 100;
    }

    public function profitStacking(int $days = 1)
    {
        $profit = 0;
        $days = $days <= 0 ? 1 : $days;
        foreach ($this as $item) {
            $profit += $item->coinPrice() * ($item->stacking->profitDay() * $days);
        }

        return $profit;
    }

    /**
     * Расчет распределения монет
     * @return $this
     */
    public function calculateRatio(): static
    {
        $total = $this->currentPrice();

        /* @var PortfolioItem $item */
        foreach ($this as $item) {
            $item->setRatio($item->currentPrice() / $total * 100);
        }

        return $this;
    }

    /**
     * Поиск монеты в портфеле
     * @param Coin $coin
     * @return PortfolioItem|null
     */
    public function findCoin(Coin $coin): ?PortfolioItem
    {
        return $this->filter(function($portfolio) use ($coin) {
            $uuid = !is_string($coin->getKey())
                ? $coin->getKey()->toString()
                : $coin->getKey();

            return $portfolio->coin->getKey() === $uuid;
        })->first();
    }

    public function findCoinByUuid(string $coin_uuid): ?PortfolioItem
    {
        return $this->filter(function($portfolio) use ($coin_uuid) {
            return $portfolio->coin->getKey() === $coin_uuid;
        })->first();
    }

    /**
     * @param PeriodEnum $period
     * @param CurrencyEnum $currency
     * @param bool $groupByDay
     * @return Collection
     * @throws Exception
     */
    public function chartData(PeriodEnum $period, CurrencyEnum $currency, bool $groupByDay = false): Collection
    {
        $modifiedDate = match ($period) {
            PeriodEnum::all => '-5 year',
            PeriodEnum::days7 => '-7 day',
            PeriodEnum::days30 => '-30 day',
            PeriodEnum::days90 => '-90 day',
            default => '-1 day',
        };

        $startPeriodDate = new DateTimeImmutable($modifiedDate);


        $price_changed = collect();
        foreach ($this as $portfolio_item) {
            $first_transaction_date = new DateTimeImmutable(
                $portfolio_item->transactions->min('date_at')
            );

            $startDate = $this->datePeriod($startPeriodDate, $first_transaction_date);

            $coin_price_change = CoinPriceStatisticAction::handle(
                $portfolio_item->coin,
                $startDate);


            $key = $coin_price_change->first()->first()->coin_uuid;

            $price_changed[$key] = $coin_price_change->first();
        }


        if($currency->name != CurrencyEnum::USD->name) {

            $coin = Coin::where('symbol', $currency->name)->first();

            $baseCoinPriceChange = CoinPriceStatisticAction::handle($coin, $startPeriodDate)->first();

        }

//        if (!$price_changed->count()) {
//            throw new PortfolioException('Недостаточно данных для расчета.');
//        }

        $result = [];
        foreach ($price_changed as $uuid => $data) {
            $firstTransaction = $this->findCoinByUuid($uuid)
                ->transactions
                ->sortBy('date_at')
                ->first();

            $firstTransactionDate = (new DateTimeImmutable($firstTransaction->date_at))->format('Y-m-d');
//            dd($firstTransactionDate, $firstTransaction);

            if ($groupByDay) {
                $data = $data->groupBy('DATE')->map(function($day) {
                    return $day->last();
                });
            }

            $coin_quantity = $this->findCoinByUuid($uuid)->quantity();

            foreach ($data as $item) {
                $key = $groupByDay ? $item->DATE : $item->last_updated;

                if (!isset($result[$key])) {
                    $result[$key] = [
                        'last_updated' => $item->last_updated,
                        'value' => 0
                    ];
                }

                $price = $firstTransactionDate == $item->DATE
                    ? $firstTransaction->price
                    : $item->price;

                if($currency->name != CurrencyEnum::USD->name && $baseCoinPriceChange->isNotEmpty()){
                    $baseCoinPrice = $baseCoinPriceChange->firstWhere('DATE', $item->DATE);
                    $price /= $baseCoinPrice->price;
                }

                $result[$key]['value'] += $coin_quantity * $price;
            }
        }

        ksort($result);
        return collect(array_values($result));
    }

    /**
     * @param DateTimeImmutable $startPeriodDate
     * @param DateTimeImmutable $first_date
     * @return DateTimeImmutable
     * @throws PortfolioException
     */
    private function datePeriod(DateTimeImmutable $startPeriodDate, DateTimeImmutable $first_date): DateTimeImmutable
    {
        $min_date_portfolio = Transaction::query()
            ->where('portfolio_id', $this->first()->portfolio_id)
            ->min('date_at');

        if (!$min_date_portfolio) {
            throw new PortfolioException('Добавьте активы в портфель.');
        }

        return max($startPeriodDate, $first_date);
    }
}
