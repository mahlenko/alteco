<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Categories;
use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Category;
use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\CoinCategory;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Request;
use Blackshot\CoinMarketSdk\Response;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @see https://coinmarketcap.com/api/documentation/v1/#operation/getV1CryptocurrencyCategory
 */
class CoinCategoryCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'blackshot:coin:category {days}';

    /**
     * @var string
     */
    protected $description = 'Объединит монеты в категории.';

    public function handle(): int
    {
        $days = intval($this->argument('days'));

        $categories = DB::table('categories')
            ->select(['uuid', 'id', 'name'])
            ->where('last_updated', '>=', new DateTimeImmutable('-'. $days .' days'))
            ->get();

        if (!$categories) {
            $this->info('Нет категорий ожидающих обновления.');
            return self::SUCCESS;
        }

        $categories->each(function($category) {
            $method = new Category([
                'id' => $category->id,
                'limit' => 1000
            ]);

            $response = (new Request())->run($method);

            if (!$response->ok || !$response->data) {
                $this->error('CoinMarket API: '. $response->description);
            }

            /*  */
            self::updateCategory($category->uuid, $response->data);
            self::appendCategoryCoins($category->uuid, $response->data['coins']);
        });

        return self::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private static function updateCategory(string $uuid, array $data): int
    {
        return DB::table('categories')
            ->where('uuid', $uuid)
            ->update([
                'num_tokens' => $data['num_tokens'],
                'last_updated' => new DateTimeImmutable($data['last_updated']),
                'avg_price_change' => $data['avg_price_change']
            ]);
    }

    private static function appendCategoryCoins(string $uuid, array $coins)
    {
        $coins = DB::table('coins')
            ->select(['uuid'])
            ->whereIn('id', array_column($coins, 'id'))
            ->pluck('uuid');

        if (!$coins) {
            return;
        }

        $data = [];
        foreach ($coins as $coin_uuid) {
            $data[] = [
                'coin_uuid' => $coin_uuid,
                'category_uuid' => $uuid
            ];
        }

        CoinCategory::upsert($data, ['coin_uuid', 'category_uuid']);
    }
}
