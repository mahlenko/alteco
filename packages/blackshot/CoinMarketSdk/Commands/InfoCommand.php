<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Info;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\CoinInfoRepository;
use Blackshot\CoinMarketSdk\Request;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
 */
class InfoCommand extends \Illuminate\Console\Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:info {--chunk=100 : Количество получаемых валют за 1 проход.}';

    /**
     * @var string
     */
    protected $description = 'Получить подробную информацию по валютам';

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle(): int
    {
        $coins = Coin::all();

        if (!$coins->count()) {
            $this->error('Сначала выгрузите список валют.');
            return self::FAILURE;
        }

        $chunk_size = intval($this->option('chunk')) ?: 100;
        $chunk_size = abs($chunk_size);

        foreach ($coins->pluck('id')->chunk($chunk_size) as $chunk) {
            $info = new Info();
            $info->id = $chunk->join(',');

            $response = (new Request())->run($info);

            if (!$response->ok) {
                $this->error('Ошибка запроса.');
                return self::FAILURE;
            }

            if (!$response->data || !count($response->data)) {
                $this->error('Сервер API не вернул список валют.');
                return self::FAILURE;
            }

            //
            foreach ($response->data as $info) {
                CoinInfoRepository::create(
                    $coin = $coins->where('id', $info->id)->first(),
                    $info->category,
                    $info->logo,
                    $info->description,
                    $info->notice,
                    new DateTimeImmutable($info->date_added),
                    $info->tags,
                    $info->urls
                );

                $this->info('Update: ' . $coin->name);
            }
        }

        return self::SUCCESS;
    }
}
