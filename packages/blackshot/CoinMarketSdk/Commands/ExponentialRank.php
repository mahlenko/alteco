<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Расчет экспоненциального ранк
 */
class ExponentialRank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:rank:exponential';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Расчет экспоненциального ранка';

    const ALPHA_SMOOTH = 0.5;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws Exception
     */
    public function handle()
    {
        $ranks = Signal::where('date', '>', (new DateTimeImmutable('-3 month'))->format('Y-m-d'))
            ->get()
            ->groupBy('coin_uuid');

        foreach ($ranks as $coin_ranks) {
            //
//            $exponential_rank = self::exponential($coin_ranks->pluck('rank'));
            $exponential_rank = self::exponentialUp($coin_ranks->pluck('rank'));

            //
            DB::table('coins')
                ->where('uuid', $coin_ranks->first()->coin_uuid)
                ->update(['exponential_rank' => $exponential_rank]);
        }

        return 0;
    }

    /**
     * Формула расчета экспоненциального сглаживания
     * @param Collection $series
     * @param float $alpha_smooth
     * @return int
     */
    public static function exponential(Collection $series, float $alpha_smooth = self::ALPHA_SMOOTH): int
    {
        if ($alpha_smooth > 1 || $alpha_smooth < 0) {
            throw new InvalidArgumentException('Alpha сглаживание должно быть в пределах от 0 до 1.');
        }

        // в качестве первого значения возьмем среднюю части серии
        $predict_value = $series->take($series->count() / 100 * 10)->average();

        foreach ($series as $item) {
            $predict_value = $alpha_smooth * $item + (1 - $alpha_smooth) * $predict_value;
        }

        return intval($predict_value);
    }

    /**
     * Расчет экспоненциального роста
     * @see https://www.rapidtables.org/ru/calc/math/exponential-growth-calculator.html
     * @param Collection $series
     * @return float|int
     */
    public static function exponentialUp(Collection $series): float|int
    {
        // начальное значение
        $begin = $series->first();

        // темп роста
        $growthRate = self::growthRate($series);

        // статистика хранит информацию за 1 день,
        // поэтому период можем взять просто по количеству записей
        $period = $series->count();

        // Формула расчета:
        // х(t) = х0 × (1 + r)t
        // --------------------------------------------------
        // x(t) - значение в момент времени t
        // x0 - начальное значение в момент времени t = 0
        // r - скорость роста, когда r/0, или скорость распада, когда r<0, в процентах
        // t - время в дискретных интервалах и выбранных единицах времени.

        return $begin * pow(1 + $growthRate, $period);
    }

    /**
     * Расчет темпа роста
     * @see http://ru.solverbook.com/spravochnik/formuly-po-ekonomike/formula-tempa-rosta
     * @param Collection $series
     * @return float
     */
    private static function growthRate(Collection $series): float
    {
        return $series->first() / $series->last();
    }
}
