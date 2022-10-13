<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Database\factories;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortfolioFactory extends Factory
{
    protected $model = Portfolio::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create(),
            'name' => $this->faker->word
        ];
    }
}
