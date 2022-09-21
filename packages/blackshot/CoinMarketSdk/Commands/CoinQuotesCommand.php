<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Quotes\Latest;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Request;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

/**
 * Загрузит цены и рейтинг монет
 * @see https://coinmarketcap.com/api/documentation/v1/#operation/getV2CryptocurrencyQuotesLatest
 */
class CoinQuotesCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:quotes';

    /**
     * @var string
     */
    protected $description = 'Цены и рейтинг монет';

    public function handle(): int
    {
        $this->info($this->description);

        /*
         | Максимальное количество монет за 1 запрос 100 штук
         | Ограничение API.
        */
        CoinRepository::handle()->chunk(100)->each(function($chunk) {
            /* @var Collection $chunk */
            $method = new Latest(['id' => $chunk->pluck('id')->join(',')]);

            /* Получаем данные по ранку и стоимости */
            $response = (new Request())->run($method);
            if (!$response->ok || !$response->data) {
                $this->error('CoinMarket API: '. $response->description);
                return;
            }

            $result = self::store($chunk, $response->data);

            $this->table(array_keys($result[0]), $result);
        });

        $this->call('blackshot:coin:ratio');
        $this->call('blackshot:coin:signals');
        $this->call('blackshot:coin:exponential');

        $this->call('cache:forget', ['key' => 'coins']);

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

            try {
                $quote = $coin->attachQuote(array_merge(
                    (array)$token->quote->USD,
                    [
                        'currency' => 'USD',
                        'cmc_rank' => $token->cmc_rank,
                        'max_supply' => $token->max_supply,
                        'circulating_supply' => $token->circulating_supply,
                        'total_supply' => $token->total_supply,
                    ]
                ));
            } catch (Exception $exception) {
                if ($exception->getCode() === 604) {
                    // Код 604 вернется если rank не был передан,
                    // скорее всего такой монеты больше нет, поэтому мы ее удалим
                    $coin->delete();
                    $this->warn('Монета удалена из БД, так как больше не существует.');
                } else {
                    $this->warn($exception->getMessage());
                }
            }

            $result[] = [
                'name' => $token->name,
                'rank' => $token->cmc_rank,
                'price' => $token->quote->USD->price,
                'created_at' => $quote->created_at
            ];
        }

        return $result;
    }
}
