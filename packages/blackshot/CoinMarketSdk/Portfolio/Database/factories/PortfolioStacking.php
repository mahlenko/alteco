<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Database\factories;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Stacking;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortfolioStacking extends Factory
{
    protected $model = Stacking::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'portfolio_id' => Portfolio::factory(),
            'amount' => $this->faker->randomFloat(5),
            'apy' => $this->faker->randomFloat(2, 0.1, 100),
            'stacking_at' => $this->faker->date
        ];
    }
}
