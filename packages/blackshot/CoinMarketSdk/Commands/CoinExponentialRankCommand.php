<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Расчет экспоненциального ранк
 */
class CoinExponentialRankCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blackshot:coin:exponential';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Расчет экспоненциального ранка';

    const ALPHA_SMOOTH = 0.6;

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
    public function handle(): int
    {
        $builder = DB::table('signals');
        $signals = $builder->select(['coin_uuid', 'rank'])
            ->get()
            ->mapToGroups(function($item) {
                return [ $item->coin_uuid => $item->rank ];
            });

        foreach ($signals as $coin_uuid => $ranks) {
            /* Рассчитаем экспоненциальный ранк */
            try {
                $ema = self::ema($ranks);
            } catch (RuntimeException $exception) {
                $this->error($exception);
                return self::FAILURE;
            }

            if (!$ema) continue;

            DB::table('coins')
                ->where('uuid', $coin_uuid)
                ->update([
                    'exponential_rank' => $ema
                ]);
        }

        return self::SUCCESS;
    }

    /**
     * @param Collection|array $data
     * @param int $timePeriod
     * @return int|null
     */
    public static function ema(Collection|array $data, int $timePeriod = 2): ?int
    {
        if (!function_exists('trader_ema')) {
            throw new RuntimeException('Установите pecl пакет "trader".');
        }

        if ($data instanceof Collection) {
            $data = $data->toArray();
        }

        $result_ema = trader_ema($data, $timePeriod);

        if (!$result_ema) return null;
        $result_ema = array_values($result_ema);

        // вычисляем как нужно Александру
        $result = 1001 - $result_ema[count($result_ema) - 1];

        return $result > 0 ? intval($result) : null;
    }
}
