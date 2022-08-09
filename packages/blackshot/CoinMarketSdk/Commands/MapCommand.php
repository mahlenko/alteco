<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Map;
use Blackshot\CoinMarketSdk\Models\Platform;
use Blackshot\CoinMarketSdk\Models\Setting;
use Blackshot\CoinMarketSdk\Models\Signal;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Request;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Ramsey\Uuid\Uuid;


/**
 *
 */
class MapCommand extends \Illuminate\Console\Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:map {?--start=1 : Начальная позиция получения данных}
        {?--limit=1000 : Количество получаемых данных. Максимум: 5000}';

    /**
     * @var string
     */
    protected $description = 'Загрузить список валют';

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function handle(): int
    {
        $map = new Map();

        $max_coins = Setting::getByKey('loading_coins_position')->value;

        $key = 'loading_coins_latest';
        $setting_latest = Setting::getByKey($key);

        $latest = $setting_latest
            ? $setting_latest->value
            : 1;

        $limit = intval($this->option('limit'));

        if ($latest + $limit > $max_coins) {
            $diff = ($latest + $limit) - $max_coins;
            $limit = $limit - $diff;
        }

        $map->start = $latest;
        $map->limit = $limit;

        /*  */
        $response = (new Request())->run($map);

        if (!$response->ok) {
            $this->error('Ошибка запроса.');
            return self::FAILURE;
        }

        if (!$response->data || !count($response->data)) {
            $this->error('Сервер API не вернул список валют.');
            return self::FAILURE;
        }

//        $today_date = (new DateTimeImmutable('now'))->format('Y-m-d');
//        $latest_date = (new DateTimeImmutable('-1 day'))->format('Y-m-d');

//        $signals_latest = Signal::where('date', $latest_date)->get();
//        $signals_today = Signal::where('date', $today_date)->get();

        foreach ($response->data as $coin) {
            // Create new or find platform
            $platform = null;
            if ($coin->platform && $coin->platform->id) {
                $platform = Platform::where('id', $coin->platform->id)->first();
                if (!$platform) $platform = $this->addPlatform($coin->platform);
            }

            // Create new or find coin
//            $coin = CoinRepository::create(
            CoinRepository::create(
                $coin->id,
                $coin->name,
                $coin->symbol,
                $coin->slug,
                $coin->rank,
                $coin->is_active,
                $coin->first_historical_data ? new DateTimeImmutable($coin->first_historical_data) : null,
                $coin->last_historical_data ? new DateTimeImmutable($coin->last_historical_data) : null,
                $platform
            );

//            /* Save signal */
//            $signal_latest = $signals_latest->where('coin_uuid', $coin->uuid)->first();
//            $signal_today = $signals_today->where('coin_uuid', $coin->uuid)->first();
//
//            if (!$signal_today) {
//                $signal_today = new Signal();
//                $signal_today->coin_uuid = $coin->uuid;
//                $signal_today->date = $today_date;
//            }
//
//            $signal_today->rank = $coin->rank;
//
//            if ($signal_latest) {
//                $signal_today->diff = $signal_latest->rank - $signal_today->rank;
//            } else {
//                $signal_today->diff = 0;
//            }
//
//            $signal_today->save();
        }

        // update for next request
        $next_offset = $latest + $limit;
        if ($max_coins == $next_offset) $next_offset = 1;

        Setting::updateValue($key, $next_offset);

        return self::SUCCESS;
    }


    /**
     * @param $data
     * @return Platform|null
     */
    private function addPlatform($data): ?Platform
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

        return Platform::where('uuid', $uuid->toString())->first();
    }
}
