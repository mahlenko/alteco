<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Category;
use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Request;
use Blackshot\CoinMarketSdk\Response;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

/**
 *
 */
class CategoryCommand extends \Illuminate\Console\Command
{
    /**
     * @var string
     */
    protected $name = 'blackshot:category';

    /**
     * @var string
     */
    protected $description = 'Загрузит категории монеты.';

    /**
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        $limit_category = 5;
        $limit_coins = 200;

        $categories = CategoryModel::where('updated_at_coins', null)
            ->limit($limit_category)
            ->get();

        foreach ($categories as $category) {
            $pages = ceil($category->num_tokens / $limit_coins);
            for ($i = 0; $i < $pages; $i++) {
                $start = $i * $limit_coins + 1;

                try {
                    $response = $this->getData($category->id, $start, $limit_coins);
                    $this->saveData($response, $category);
                } catch (Exception $exception) {
                    $this->error($exception->getMessage());
                }
            }

            $category->updated_at_coins = (new DateTimeImmutable('now'))
                ->format('Y-m-d H:i:s');
            $category->save();
        }

        return self::SUCCESS;
    }

    /**
     * @param string $id
     * @param int $start
     * @param int $limit
     * @return Response
     * @throws GuzzleException
     */
    private function getData(string $id, int $start = 1, int $limit = 1000): Response
    {
        $api = new Category();
        $api->id = $id;
        $api->start = $start;
        $api->limit = $limit;

        return (new Request())->run($api);
    }

    /**
     * @param Response $response
     * @param CategoryModel $category
     * @return void
     */
    private function saveData(Response $response, CategoryModel $category)
    {
        $this->info('-- '. Str::upper($category->name) .' --');

        if ($response->ok) {
            $coins_id = array_column($response->data['coins'], 'id');

            if ($coins_id) {
                $coins = Coin::whereIn('id', $coins_id)->get();

                foreach ($coins as $index => $coin) {
                    if (CoinCategoryRepository::relation($coin, $category) ) {
                        //
                        $this->info($index .'. '. $coin->name);
                    } else {
                        $this->error('ERROR: Не удалось добавить монету в категорию ' . $category->name);
                    }
                }
            }

        } else {
            $this->error($response->code .': '. $response->description);
        }
    }
}
