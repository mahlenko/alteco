<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Categories;
use Blackshot\CoinMarketSdk\Repositories\CategoryRepository;
use Blackshot\CoinMarketSdk\Request;
use DateTimeImmutable;
use GuzzleHttp\Exception\GuzzleException;

/**
 *
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
        $categories = (new Request())->run($action);

        if (!$categories->ok) {
            $this->error($categories->description);
            return self::FAILURE;
        }

        foreach ($categories->data as $category)
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
            } catch (\Exception $exception) {
                $this->error('ERROR: '. $category->name);
                $this->error($exception->getMessage());
                return self::FAILURE;
            }
        }

        return self::SUCCESS;
    }
}
