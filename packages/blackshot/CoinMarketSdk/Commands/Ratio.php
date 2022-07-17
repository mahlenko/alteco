<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\CrixIndex;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Requires an PECL PHP Trader lib
 * install: pecl install trader
 */
class Ratio extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:coin:ratio';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получит коэффициенты токенов';

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
     * @see https://www.swe-notes.ru/post/exp_smoothing/article
     * @return int
     */
    public function handle()
    {
        $minimum = (new DateTimeImmutable())->modify('-1 year');

        $coins = DB::table('coins')
            ->select(['coins.uuid', 'coins.name', 'coin_quotes.price', 'coin_quotes.last_updated'])
            ->join('coin_quotes', 'coins.uuid', 'coin_quotes.coin_uuid')
            ->where('coin_quotes.last_updated', '>=', $minimum)
            ->get()
                ->sortBy('last_updated')
                ->groupBy('uuid');

        foreach ($coins as $prices) {
            $this->info($prices->first()->name);

            $beta_list_data = self::beta($prices);

            if ($beta_list_data) {
                //
                $beta = $beta_list_data[count($beta_list_data) - 1];
                $this->warn('Beta: '. $beta);

                //
                $alpha = self::alpha($prices, $beta);
                $this->warn('Alpha: '. $alpha);

                //
                $squid = self::squid($prices);
                $this->warn('Squid: '. $squid);

                //
                $result = DB::table('coins')
                    ->where('uuid', $prices->first()->uuid)
                    ->update([
                        'beta' => $beta,
                        'alpha' => $alpha,
                        'squid' => $squid
                    ]);

                if ($result) {
                    $this->info('Done');
                }
            }
        }

        return 0;
    }

    /**
     * @param Collection $price_collection
     * @param int|null $periodDays
     * @return array|null
     * @throws Exception
     */
    public static function beta(Collection $price_collection): ?array
    {
        if (!function_exists('trader_beta')) {
            dd('Run `pecl install trader` PHP extension.');
        }

        // доходность инвестиций
        $profit = self::profit($price_collection);
        if (!$profit->count()) return null;

        // доходность рынка
        $crix = self::crix($price_collection);
        if (!$crix->count()) return null;

        // Сравниваем даты, чтобы данные совпадали и были одного количества
        $profit_dates = $profit->keys();
        $crix_dates = $crix->keys();

        $crix = $crix->only($profit_dates);
        $profit = $profit->only($crix_dates);

        $timePeriod = $profit->count() / 4;
        if ($timePeriod < 1) $timePeriod = 1;

        //
        $beta = trader_beta($profit->toArray(), $crix->toArray(), $timePeriod);

        return is_array($beta) ? array_values($beta) : null;
    }

    /**
     * @param Collection $price_collection
     * @param float $beta
     * @param float $noRisk
     * @return float
     * @throws Exception
     */
    public static function alpha(Collection $price_collection, float $beta, float $noRisk = 9.8): float
    {
        $profit_invest = self::profit($price_collection);
        $profit_market = self::crix($price_collection);

        return round(
            $profit_invest->average() - ($noRisk + $beta * ($profit_market->average() - $noRisk)),
            2
        );
    }

    /**
     * @param Collection $price_collection
     * @return float
     * @throws Exception
     */
    public static function squid(Collection $price_collection): float
    {
////        $profit = self::profit($price_collection)->values();
//
//        $collection = $price_collection->pluck('price');
//
//        $avg_geometry = sqrt($collection->first() * $collection->last());
//
////        $avg_geometry = $profit->map(function($price) {
////            return round($price, 5);
////        })->values()->toArray();
////
////        $string = number_format(
////            abs(array_product($avg_geometry)),
////            0,
////            '',
////            ''
////        );
//
//        $max_drawdown = 0;
//        foreach ($collection as $index => $current_profit) {
//            if (!$index) continue;
//
//            $drawdown = $current_profit - $collection[$index - 1];
//            if ($drawdown < 0 && $drawdown < $max_drawdown) {
//                $max_drawdown = $drawdown;
//            }
//        }
//
//        return $avg_geometry / $max_drawdown;

//        $product = self::expFormat(
//            array_product(array_filter($profit->toArray()))
//        );

        $profit = $price_collection->pluck('price')
            ->map(function($price) {
                return round($price, 2);
            });

        $prices = [];
        foreach ($profit as $index => $price) {
            if (!$index) continue;

            $before = $profit[$index - 1];
            if (!$before || !$price) {
                $prices[] = 0;
                continue;
            }

            $prices[] = round($price / $before, 5);
        }

        $average_sqrt = sqrt(array_product($prices));

        $max_drawdown = 0;
        foreach ($profit as $index => $current_profit) {
            if (!$index) continue;

            if ($current_profit < $profit[$index - 1]) {
                $drawdown = round($current_profit / $profit[$index - 1], 5);
                if ($drawdown > $max_drawdown) {
                    $max_drawdown = $drawdown;
                }
            }
        }

        return $average_sqrt == 0 || $max_drawdown == 0
            ? 0
            : round($average_sqrt / $max_drawdown, 2);
    }

    /**
     * Доходность инвестиций
     * @param Collection $collection
     * @return Collection
     * @throws Exception
     */
    private static function profit(Collection $collection): Collection
    {
        $collection = $collection->map(function($item) {
            $item->last_updated = (new DateTimeImmutable($item->last_updated))
                ->format('Y-m-d');
            return $item;
        })->unique('last_updated')->values();

        $result = collect();
        foreach ($collection as $index => $item) {
            if ($index % 2 != 0) {
                $result->put(
                    (new DateTimeImmutable($item->last_updated))->format('Y-m-d'),
                    self::profitPercent(
                        $collection[$index - 1]->price,
                        $item->price,
                    )
                );
            }
        }

        return $result;
    }

    /**
     * Доходность рынка
     * @param Collection $collection
     * @return Collection
     */
    private static function crix(Collection $collection): Collection
    {
        $dates = $collection->pluck('last_updated')->map(function($date) {
            return (new DateTimeImmutable($date))->format('Y-m-d');
        })->unique()->values();

        $crixIndices = CrixIndex::whereIn('date', $dates)->get();

        $result = collect();
        foreach ($crixIndices as $index => $crix) {
            if ($index % 2 != 0) {
                $result->put(
                    $crix->date->format('Y-m-d'),
                    self::profitPercent(
                        $crixIndices[$index - 1]->index,
                        $crix->index,
                    )
                );
            }
        }

        return $result;
    }

    /**
     * Расчет профита в процентах
     * @param float $buy
     * @param float $current
     * @return float
     * @see https://journal.tinkoff.ru/indicators/?ysclid=l5fy2ow5sm924408326
     */
    private static function profitPercent(float $buy, float $current): float
    {
        $profit = ($current - $buy) / $buy;
//        dd($profit * 100, self::expFormat($profit * 100));

        return $profit * 100;
//        return self::expFormat($profit * 100);
    }

    private static function expFormat(float|string $number): float
    {
        $result = $number;

        $dot_pos = strpos((string) $result, '.');

        if (Str::contains($result, 'E')) {
            $result = Str::substr($result, 0, $dot_pos + 3);
        }

        if (Str::length(Str::substr($result, $dot_pos)) > 4) {
            $result = Str::substr($result, 0, $dot_pos + 3);
        }

        return floatval($result);
    }
}
