<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Map;
use Blackshot\CoinMarketSdk\Models\Platform;
use Blackshot\CoinMarketSdk\Models\Setting;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Request;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;

/**
 *
 */
class CoinLoadCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:load';

    /**
     * @var string
     */
    protected $description = 'Загрузить список монет';

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle(): int
    {
        $this->info($this->description);

        $method = new Map([
            'limit' => Setting::getByKey('loading_coins_position')->value
        ]);

        $response = (new Request)->run($method);

        if (!$response->ok && !$response->data) {
            return self::FAILURE;
        }

        foreach ($response->data as $coin) {
            // Добавим в БД новые платформы или просто выберем их,
            // чтобы присвоить монете.
            $platform = null;

            if (isset($coin->platform->id)) {
                $platform = Platform::where('id', $coin->platform->id)->first();

                if (!$platform) {
                    $platform = self::addPlatform($coin->platform);
                }
            }

            /* Каст для дат */
            foreach (['first_historical_data', 'last_historical_data'] as $key) {
                if (isset($coin->$key)) {
                    $coin->$key = new DateTimeImmutable($coin->$key);
                } else $coin->$key = null;
            }

            CoinRepository::create(
                $coin->id,
                $coin->name,
                $coin->symbol,
                $coin->slug,
                $coin->rank,
                $coin->is_active,
                $coin->first_historical_data,
                $coin->last_historical_data,
                $platform
            );
        }

        /* Получим инфу для новых токенов */
        $this->call('blackshot:coin:info');

        /* Получим цену для токенов */
        $this->call('blackshot:coin:quotes');

        return self::SUCCESS;
    }

    /**
     * @param $data
     * @return Platform|null
     */
    private static function addPlatform($data): ?Platform
    {
        $uuid = Uuid::uuid4();

        $platform = new Platform([
            'uuid' => $uuid->toString(),
            'id' => $data->id,
            'name' => $data->name,
            'symbol' => $data->symbol,
            'slug' => $data->slug,
            'token_address' => $data->token_address
        ]);

        $platform->save();

        return Platform::find($uuid);
    }
}
