<?php

namespace Blackshot\CoinMarketSdk\Actions\Calculate\Coeficents;

class AlphaAction
{
    const NO_RISK = 9.8;

    /**
     * @param float $priceProfitAvg
     * @param float $crixProfitAvg
     * @param float $beta
     * @param float $noRisk
     * @return float
     */
    public static function handle(
        array $prices,
        array $crix,
        float $beta,
        float $noRisk = self::NO_RISK
    ): float {
        /* Расчет среднего прироста */
        $growth = [];
        foreach ($crix as $index => $value) {
            if ($index % 2 === 0) continue;
            $growth[] = $value - $crix[$index - 1];
        }

        $growthAvg = array_sum($growth) / count($growth);

        /* Расчет средней доходности актива */
        $profit = [];
        foreach ($prices as $index => $price) {
            if ($index % 2 === 0) continue;
            $profit[] = ($price - $prices[$index - 1]) / $price * 100;
        }

        $profitAvg = array_sum($profit) / count($profit);

        $alpha = $profitAvg - ($noRisk + $beta * ($growthAvg - $noRisk));

        return round($alpha, 2);
    }
}
