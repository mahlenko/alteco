<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Quote;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\DB;

/**
 *
 */
class QuotesGroupDay extends \Illuminate\Console\Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:quotes:group';

    /**
     * @var string
     */
    protected $description = 'Сгруппирует цены по дню';

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle(): int
    {
        $coins = Coin::all(['uuid', 'name', 'symbol']);

        $bar = $this->output->createProgressBar(count($coins));
        $bar->start();

        foreach ($coins as $coin) {
            // цены
            $quotes = Quote::select(['uuid', 'last_updated'])
                ->where('coin_uuid', $coin->uuid)
                ->get()
                ->sortBy('last_updated')
                ->groupBy(function($coin) {
                    return $coin->last_updated->format('Y-m-d');
                });

            $delete_uuids = [];

            foreach ($quotes as $quote) {
                if ($quote->count() > 1) {
                    $delete_uuids = array_merge(
                        $delete_uuids,
                        $quote
                            ->where('uuid', '<>', $quote->last()->uuid)->pluck('uuid')
                            ->toArray()
                    );
                }
            }

            if ($delete_uuids && count($delete_uuids)) {
                DB::table('coin_quotes')
                    ->where('coin_uuid', $coin->uuid)
                    ->whereIn('uuid', $delete_uuids)
                    ->delete();
            }

            $bar->advance();
        }

        $bar->finish();

        return self::SUCCESS;
    }
}
