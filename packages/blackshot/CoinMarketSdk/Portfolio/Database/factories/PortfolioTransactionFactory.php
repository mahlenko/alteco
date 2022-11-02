<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Database\factories;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransferTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Factories\Factory;

class PortfolioTransactionFactory extends Factory
{
    protected $model = Transaction::class;

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
            'coin_uuid' => Coin::factory(),
            'price' => $this->faker->randomFloat(min: 0.000001),
            'quantity' => $this->faker->randomFloat(min: 0.000001),
            'fee' => $this->faker->randomFloat(5, 0, 5),
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy->name,
            'transfer_type' => null
        ];
    }

    public function buy()
    {
        return $this->state(function(array $attributes) {
            return ['type' => TransactionTypeEnum::Buy->name];
        });
    }

    public function sell()
    {
        return $this->state(function(array $attributes) {
            return ['type' => TransactionTypeEnum::Sell->name];
        });
    }

    public function transferIn()
    {
        return $this->state(function(array $attributes) {
            return [
                'type' => TransactionTypeEnum::Transfer,
                'transfer_type' => TransferTypeEnum::In
            ];
        });
    }

    public function transferOut()
    {
        return $this->state(function(array $attributes) {
            return [
                'type' => TransactionTypeEnum::Transfer,
                'transfer_type' => TransferTypeEnum::Out
            ];
        });
    }
}
