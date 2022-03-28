<?php

namespace Blackshot\CoinMarketSdk\Commands;

use Blackshot\CoinMarketSdk\Methods\Cryptocurrency\Categories;
use Blackshot\CoinMarketSdk\Models\CategoryModel;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Repositories\CoinCategoryRepository;
use Blackshot\CoinMarketSdk\Request;
use DateTimeImmutable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 *
 */
class CoinCategoryCommand extends \Illuminate\Console\Command
{
    protected $name = 'blackshot:coin:category';

    protected $description = 'Загрузит категории монеты.';

    public function handle()
    {
        $coins = Coin::where(function($builder) {
            $builder->where('updated_at_categories', null);
            $builder->orWhere('updated_at_categories', '<=', (new DateTimeImmutable('-7 days'))->format('Y-m-d'));
        })->limit(30)->get();

        if (!$coins) {
            dump('Всем монетам присвоены категории.');
            return self::SUCCESS;
        }

        foreach ($coins as $coin) {
            $categories_api = new Categories();
            $categories_api->symbol = $coin->symbol;

            $response = (new Request())->run($categories_api);

            if (!$response->ok) {
                if ($response->code == 400) {
                    if (Str::contains($response->description, 'Invalid value for "symbol":')) {
                        $coin->updated_at_categories = (new DateTimeImmutable('now'))
                            ->format('Y-m-d H:i:s');
                        $coin->save();
                    }

                }

                $this->error($response->code .': '. $response->description);

                if ($response->code === 1008) {
                    return self::INVALID;
                }
            }

            //
            $coin->updated_at_categories = (new DateTimeImmutable('now'))
                ->format('Y-m-d H:i:s');
            $coin->save();

            //
            $categories = CategoryModel::whereIn('id', array_column($response->data, 'id'))
                ->get();

            if (!$categories) {
                $error = 'В БД не найдено категорий монеты. Обновите список категорий.';

                $this->error($error);
                Log::info($error);
            }

            $this->info($coin->name .': '. $categories->pluck('name')->join(', '));

            foreach ($categories as $category) {
                if (CoinCategoryRepository::relation($coin, $category) ) {
                    //
                } else {
                    dump('ERROR: Не удалось добавить категорию ' . $category->name);
                }
            }
        }

        return self::SUCCESS;
    }
}
