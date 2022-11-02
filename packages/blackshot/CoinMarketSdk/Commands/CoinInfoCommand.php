<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Info;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\CoinInfoRepository;
use Blackshot\CoinMarketSdk\Request;
use DateTimeImmutable;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 *
 */
class CoinInfoCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:info';

    /**
     * @var string
     */
    protected $description = 'Получить подробную информацию по валютам';

    /**
     * @see https://coinmarketcap.com/api/documentation/v1/#operation/getV1CryptocurrencyMap
     * @return int
     * @throws Exception
     */
    public function handle(): int
    {
        /* Получим список монет, у которых еще не выгружали инфу */
        $coins = DB::table('coins')
            ->select(['coins.uuid', 'coins.id'])
            ->join('coin_info', 'coins.uuid', '=', 'coin_info.coin_uuid', 'left')
            ->whereNull('coin_info.coin_uuid')
            ->get();

        if (!$coins) {
            $this->info('Нет токенов для выгрузки информации о них.');
            return self::SUCCESS;
        }

        $method = new Info();
        $result = [];

        $coins->chunk(100) /* Метод максимум выдает информации по 100  */
            ->each(function($symbols) use ($method, &$result) {
                /* */
                $method->id = $symbols
                    ->pluck('id')
                    ->join(',');

                /* */
                $response = (new Request())->run($method);
                if (!$response->ok || !$response->data) {
                    $this->error('CoinMarket API: '. $response->description);
                    return;
                }

                /* Добавляем инфо по монетам */
                $symbols->each(function($coin) use ($response, &$result) {
                    if (!key_exists($coin->id, $response->data)) {
                        return;
                    }

                    $info = $response->data[$coin->id];
                    if (!$info) return;

                    /* Инфо возвращается массивом */
//                    $info = $info[0];

                    /* Удалим уже не существующие монеты */
                    if ($info->status != 'active') {
                        DB::table('coins')
                            ->where('uuid', $coin->uuid)
                            ->delete();

                        return;
                    }

                    /* Каст для даты */
                    $added = isset($info->date_added) && $info->date_added
                        ? new DateTimeImmutable($info->date_added)
                        : new DateTimeImmutable();

                    /* Добавим инфу по монете */
                    $item = CoinInfoRepository::create(
                        Coin::query()->find($coin->uuid),
                        $info->category,
                        $info->logo ?? null,
                        $info->description ?? null,
                        $info->notice ?? null,
                        $added,
                        $info->tags,
                        $info->urls
                    );

                    $result[] = [
                        'name' => $info->name,
                        'category' => $item->category,
                        'logo' => $item->logo,
                        'description' => Str::limit($item->description, 20),
                        'date_added' => $item->date_added
                    ];
                });
            });

        if ($result) {
            $this->table(array_keys($result[0]), $result);
        }

        return self::SUCCESS;
    }
}
