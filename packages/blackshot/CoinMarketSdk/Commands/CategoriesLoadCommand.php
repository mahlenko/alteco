<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Categories;
use Blackshot\CoinMarketSdk\Repositories\CategoryRepository;
use Blackshot\CoinMarketSdk\Request;
use DateTimeImmutable;
use Exception;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Загрузит или обновит список категорий
 */
class CategoriesLoadCommand extends \Illuminate\Console\Command
{
    /**
     * @var string
     */
    protected $name = 'blackshot:category:load';

    /**
     * @var string
     */
    protected $description = 'Загрузит доступные категории.';

    /**
     * @throws GuzzleException
     */
    public function handle(): int
    {
        $action = new Categories();
        $response = (new Request())->run($action);

        if (!$response->ok || !$response->data) {
            $this->error('CoinMarket API: '. $response->description);
            return self::FAILURE;
        }

        foreach ($response->data as $category)
        {
            try {
                CategoryRepository::createOrUpdate(
                    $category->id,
                    $category->name,
                    $category->title,
                    $category->description,
                    $category->num_tokens,
                    $category->avg_price_change,
                    $category->market_cap,
                    $category->market_cap_change,
                    $category->volume,
                    $category->volume_change,
                    new DateTimeImmutable($category->last_updated)
                );
            } catch (Exception $exception) {
                $this->error('ERROR: '. $category->name);
                $this->error($exception->getMessage());
                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}
