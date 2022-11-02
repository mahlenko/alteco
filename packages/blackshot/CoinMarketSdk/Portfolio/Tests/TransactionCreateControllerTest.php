<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Portfolio $portfolio;
    private Coin $coin;

    public function test_unauthorized()
    {
        $this
            ->postJson(route('api.portfolio.transaction.create'))
            ->assertUnauthorized();
    }

    public function test_validation()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('api.portfolio.transaction.create'));

        $response->assertJsonValidationErrors([
            'user_id',
            'portfolio_id',
            'coin_uuid',
            'quantity',
            'price',
            'type',
        ]);
    }

    public function test_success()
    {
        $request = $this
            ->actingAs($this->user)
            ->postJson(route('api.portfolio.transaction.create'), $data = [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'price' => 25,
                'quantity' => 2,
                'fee' => 0,
                'date_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                'type' => TransactionTypeEnum::Buy->name
            ]);

        $this->assertDatabaseCount(Transaction::class, 1);
        $this->assertTrue($request->json('ok'));
        $this->assertNotEmpty($request->json('data.uuid'));
        $this->assertEquals(25, $request->json('data.price'));
    }

    public function test_invalid_data()
    {
        $request = $this
            ->actingAs($this->user)
            ->postJson(route('api.portfolio.transaction.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'price' => 25,
                'quantity' => 2,
                'fee' => -90,
                'date_at' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
                'type' => TransactionTypeEnum::Buy->name,
            ]);

        $this->assertDatabaseCount(Transaction::class, 0);
        $this->assertFalse($request->json('ok') || !$request->json('errors'));
   }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = Portfolio::factory()->create(['user_id' => $this->user]);
        $this->coin = Coin::factory()->create();
    }
}
