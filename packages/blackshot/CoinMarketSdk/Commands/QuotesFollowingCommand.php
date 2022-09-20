<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Quotes\Latest;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\Quote;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Request;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

/**
 *
 */
class QuotesFollowingCommand extends \Illuminate\Console\Command
{
    /**
     * @var string
     */
//    protected $signature = 'blackshot:coin:quotes {--favorite : Обновить валюты которые выбраны у пользователей}';
    protected $signature = 'blackshot:coin:quotes';

    /**
     * @var string
     */
    protected $description = 'Котировки валют';

    public function handle(): int
    {
        /*
         | Максимальное количество монет за 1 запрос 100 штук
         | Ограничение API.
        */
        CoinRepository::handle()->chunk(100)->each(function($chunk) {
            /* @var Collection $chunk */
            $method = new Latest(['id' => $chunk->pluck('id')->join(',')]);

            /* Получаем данные по ранку и стоимости */
            try {
                $response = (new Request())->run($method);

                $result = self::store($chunk, $response->data);
                $this->table(array_keys($result[0]), $result);

            } catch (Exception $exception) {
                $this->error($exception->getMessage());
                Log::error($this->signature .': '. $exception->getMessage());
                return;
            }
        });

        return $this::SUCCESS;
    }

    /**
     * @param $chunk
     * @param $data
     * @return array
     */
    private function store($chunk, $data): array
    {
        $result = [];

        foreach ($data as $token) {
            if (!isset($token->quote->USD)) {
                continue;
            }

            /* @var Coin $coin */
            $coin = $chunk->where('id', $token->id)->first();

            $quote = $coin->attachQuote(array_merge(
                (array) $token->quote->USD,
                [
                    'currency' => 'USD',
                    'cmc_rank' => $token->cmc_rank,
                    'max_supply' => $token->max_supply,
                    'circulating_supply' => $token->circulating_supply,
                    'total_supply' => $token->total_supply,
                ]
            ));

            $result[] = [
                'name' => $token->name,
                'rank' => $token->cmc_rank,
                'price' => $token->quote->USD->price,
                'created_at' => $quote->created_at
            ];
        }

        $this->call('blackshot:coin:ratio');
        $this->call('blackshot:signals');

        return $result;
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
//    public function handle_last(): int
//    {
//        if ($this->option('favorite')) {
//            $coins = Coin::favorites();
//        } else {
//            $coins = Coin::all();
//        }
//
//        if (!$coins->count()) { return self::FAILURE; }
//
//        $chunks = $coins->pluck('id')->chunk(100);
//
//        foreach ($chunks as $chunk) {
//            $quotes = new Latest();
//            $quotes->id = $chunk->join(',');
//
//            try {
//                $response = (new Request())->run($quotes);
//            } catch (Exception $exception) {
//                $this->error($exception->getMessage());
//                continue;
//            }
//
//            if (!$response->ok) {
//                $this->error('Ошибка запроса');
//                return self::FAILURE;
//            }
//
//            if (!$response->data || !count($response->data)) {
//                $this->error('Не получены данные по котировкам валют.');
//                return self::FAILURE;
//            }
//
//            foreach ($response->data as $data) {
//                /* @var Coin $coin */
//                $coin = $coins->where('id', $data->id)->first();
//                if (!$coin) continue;
//
//                foreach ($data->quote as $currency => $quote) {
//                    try {
//                        $this->comment('Сохраняю '. $coin->name);
//                        $coin->attachQuote($this->fillQuote($data, $quote, $currency));
//
//                        if ($currency == 'USD') {
//                            $coin->price = $quote->price;
//                            $coin->percent_change_1h = $quote->percent_change_1h;
//                            $coin->save();
//                        }
//                    } catch (Exception $exception) {
//                        $this->error($exception->getMessage() .' '. $exception->getFile() .' ('. $exception->getLine());
//                    }
//                }
//
//                //
//                $this->info('Caching data quotes...');
//                $coin->forgetCache();
//            }
//
//            $this->info('Sleep 60 seconds');
//
//            sleep(60);
//        }
//
//        $this->info('Обновление коэффициентов');
//        $this->call('blackshot:coin:ratio');
//        $this->call('blackshot:signals');
//
//        return self::SUCCESS;
//    }

    /**
     * @param $data
     * @param $quote
     * @param $currency
     * @return Quote
     */
//    private function fillQuote($data, $quote, $currency): Quote
//    {
//        return new Quote([
//            'uuid' => Uuid::uuid4()->toString(),
//
//            'currency' => $currency,
//            'cmc_rank' => $data->cmc_rank,
//            'max_supply' => $data->max_supply,
//            'circulating_supply' => $data->circulating_supply,
//            'total_supply' => $data->total_supply,
//
//            'price' => $quote->price,
//            'volume_24h' => $quote->volume_24h,
//            'volume_24h_reported' => $quote->volume_24h_reported,
//            'volume_7d' => $quote->volume_7d,
//            'volume_7d_reported' => $quote->volume_7d_reported,
//            'volume_30d' => $quote->volume_30d,
//            'volume_30d_reported' => $quote->volume_30d_reported,
//            'volume_change_24h' => $quote->volume_change_24h,
//            'percent_change_1h' => $quote->percent_change_1h,
//            'percent_change_24h' => $quote->percent_change_24h,
//            'percent_change_7d' => $quote->percent_change_7d,
//            'percent_change_30d' => $quote->percent_change_30d,
//            'percent_change_60d' => $quote->percent_change_60d,
//            'percent_change_90d' => $quote->percent_change_90d,
//            'market_cap' => $quote->market_cap,
//            'market_cap_dominance' => $quote->market_cap_dominance,
//            'fully_diluted_market_cap' => $quote->fully_diluted_market_cap,
//            'market_cap_by_total_supply' => $quote->market_cap_by_total_supply,
//            'last_updated' => $quote->last_updated
//        ]);
//    }
}
