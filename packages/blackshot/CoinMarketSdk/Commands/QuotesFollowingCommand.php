<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Quotes\Latest;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Quote;
use Blackshot\CoinMarketSdk\Request;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 *
 */
class QuotesFollowingCommand extends \Illuminate\Console\Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:quotes {--favorite : Обновить валюты которые выбраны у пользователей}';

    /**
     * @var string
     */
    protected $description = 'Котировки валют подписчиков';

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle(): int
    {
        if ($this->option('favorite')) {
            $coins = Coin::favorites();
        } else {
            $coins = Coin::all();
        }

        if (!$coins->count()) {
            $this->error('Нет списка валют.');
            return self::FAILURE;
        }

        $chunks = $coins->pluck('id')->chunk(100);

        $this->withProgressBar($chunks->count(), function(ProgressBar $bar) use ($chunks, $coins) {
            $bar->setMessage('');
            $bar->setFormat("%current%/%max% [<info>%bar%</info>] %percent:3s%% %message%");

            $bar->advance();

            foreach ($chunks as $chunk) {
                $quotes = new Latest();
                $quotes->id = $chunk->join(',');

                try {
                    $bar->setMessage('<info>Отправка запроса</info>');
                    $response = (new Request())->run($quotes);
                } catch (Exception $exception) {
                    $bar->setMessage('<error>'. $exception->getMessage() .'</error>');
                    $bar->advance();
                    continue;
                }

                if (!$response->ok) {
                    $this->error('Ошибка запроса');
                    return self::FAILURE;
                }

                if (!$response->data || !count($response->data)) {
                    $this->error('Не получены данные по котировкам валют.');
                    return self::FAILURE;
                }

                foreach ($response->data as $data) {
                    /* @var Coin $coin */
                    $coin = $coins->where('id', $data->id)->first();
                    if (!$coin) continue;

                    foreach ($data->quote as $currency => $quote) {
                        try {
                            $bar->setMessage('<warn>Сохраняю '. $coin->name .'</warn>');
                            $coin->attachQuote($this->fillQuote($data, $quote, $currency));

                            if ($currency == 'USD') {
                                $coin->price = $quote->price;
                                $coin->percent_change_1h = $quote->percent_change_1h;
                                $coin->save();
                            }
                        } catch (Exception $exception) {
                            $bar->setMessage('<error>'. $exception->getMessage() .' '. $exception->getFile() .' ('. $exception->getLine() .')</error>');
                        }
                    }

                    //
                    $bar->setMessage('Caching data quotes...');
                    Cache::forever($coin->cache_quotes_key, $coin->quotes);
                }

                $bar->setMessage('Sleep 60 seconds');
                $bar->advance();

                sleep(60);
            }

            $bar->finish();
            return true;
        });


        $this->info('Обновление коэффициентов');
        $this->call('blackshot:coin:ratio');

        return self::SUCCESS;
    }

    /**
     * @param $coin
     * @param $quote
     * @param $currency
     * @return Quote
     */
    private function fillQuote($coin, $quote, $currency): Quote
    {
        return new Quote([
            'uuid' => Uuid::uuid4()->toString(),
            'currency' => $currency,
            'cmc_rank' => $coin->cmc_rank,
            'max_supply' => $coin->max_supply,
            'circulating_supply' => $coin->circulating_supply,
            'total_supply' => $coin->total_supply,
            'price' => $quote->price,
            'volume_24h' => $quote->volume_24h,
            'volume_24h_reported' => $quote->volume_24h_reported,
            'volume_7d' => $quote->volume_7d,
            'volume_7d_reported' => $quote->volume_7d_reported,
            'volume_30d' => $quote->volume_30d,
            'volume_30d_reported' => $quote->volume_30d_reported,
            'volume_change_24h' => $quote->volume_change_24h,
            'percent_change_1h' => $quote->percent_change_1h,
            'percent_change_24h' => $quote->percent_change_24h,
            'percent_change_7d' => $quote->percent_change_7d,
            'percent_change_30d' => $quote->percent_change_30d,
            'percent_change_60d' => $quote->percent_change_60d,
            'percent_change_90d' => $quote->percent_change_90d,
            'market_cap' => $quote->market_cap,
            'market_cap_dominance' => $quote->market_cap_dominance,
            'fully_diluted_market_cap' => $quote->fully_diluted_market_cap,
            'market_cap_by_total_supply' => $quote->market_cap_by_total_supply,
            'last_updated' => $quote->last_updated
        ]);
    }
}
