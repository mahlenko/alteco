<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\Signal;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
            $exponential_rank = self::exponentialRank($coin_ranks->pluck('rank'));

            //
            DB::table('coins')
                ->where('uuid', $coin_ranks->first()->coin_uuid)
                ->update(['exponential_rank' => $exponential_rank]);
        }

        return 0;
    }

    /**
     * @param Collection $collection
     * @param float $alpha
     * @return float
     * @see https://excel2.ru/articles/eksponentsialnoe-sglazhivanie-v-ms-excel
     */
    public static function exponentialRank(Collection $collection, float $alpha = 0.6): ?float
    {
        $data = $collection->toArray();
        $result = collect();

        foreach ($collection as $index => $value) {
            $exp_data = [
                'index' => $index + 1,
                'value' => $value,
                'error' => null,
                'exp' => null
            ];

            if ($index) {
                $previous = $data[$index - 1];
                if (!$previous['exp']) $previous['exp'] = $previous['value'];
                $exp_data['exp'] = (1 - $alpha) * $previous['value'] + $alpha * $previous['exp'];
                $exp_data['error'] = $exp_data['value'] - $exp_data['exp'];
            }

            $data[$index] = $exp_data;
            $result->add($exp_data['exp']);
        }

        return $result->filter()->avg();
    }
}
