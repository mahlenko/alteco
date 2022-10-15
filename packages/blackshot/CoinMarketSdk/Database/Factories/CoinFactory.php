<?php

namespace Blackshot\CoinMarketSdk\Database\Factories;

use Blackshot\CoinMarketSdk\Models\Coin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class CoinFactory extends Factory
{
    protected $model = Coin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->randomDigit(),
            'rank' => $this->faker->randomDigit(),
            'rank_30d' => $this->faker->randomDigit(),
            'rank_60d' => $this->faker->randomDigit(),
            'price' => $this->faker->randomFloat(),
            'percent_change_1h' => mt_rand(-100, 100),
            'name' => $name = $this->faker->sentence(2),
            'symbol' => $this->faker->word,
            'slug' => Str::slug($name),
            'is_active' => true,
            'first_historical_data' => $this->faker->dateTime,
            'last_historical_data' => $this->faker->dateTime,
            'beta' => $this->faker->randomFloat(2, 0, 1),
            'alpha' => $this->faker->randomFloat(2),
            'squid' => $this->faker->randomFloat(2, 0, 1),
            'alteco' => mt_rand(0, 100),
            'alteco_desc' => $this->faker->sentence,
            'exponential_rank' => mt_rand(1, 1000),
            'updated_at_categories' => $this->faker->dateTime,
        ];
    }
}
