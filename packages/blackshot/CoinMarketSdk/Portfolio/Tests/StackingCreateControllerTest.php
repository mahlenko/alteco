<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Stacking;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StackingCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Portfolio $portfolio;
    private Coin $coin;

    public function test_unauthorized()
    {
        $this
            ->postJson(route('api.portfolio.stacking.create'))
            ->assertUnauthorized();
    }

    public function test_create_success()
    {
        $request = $this
            ->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), $data = [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => 0.000001,
                'apy' => 30,
                'stacking_at' => (new DateTimeImmutable('-1 minute'))->format('Y-m-d H:i:s')
            ])
            ->assertOk();

        $this->assertDatabaseCount(Stacking::class, 1);
        $this->assertEquals($request->json('data.portfolio_id'), $data['portfolio_id']);
        $this->assertEquals($request->json('data.coin_uuid'), $data['coin_uuid']);
        $this->assertEquals($request->json('data.amount'), $data['amount']);
        $this->assertEquals($request->json('data.apy'), $data['apy']);
    }

    public function test_validation_required_failure()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [])
            ->assertJsonValidationErrors(['user_id', 'portfolio_id', 'coin_uuid', 'amount', 'apy', 'stacking_at']);
    }

    public function test_negative_amount_failure()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => -0.000001,
                'apy' => 30,
                'stacking_at' => (new DateTimeImmutable('-1 minute'))->format('Y-m-d H:i:s')
            ])
            ->assertJsonValidationErrors(['amount']);
    }

    public function test_negative_apy_failure()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => 0.000001,
                'apy' => -5,
                'stacking_at' => (new DateTimeImmutable('-1 minute'))->format('Y-m-d H:i:s')
            ])
            ->assertJsonValidationErrors(['apy']);
    }

    public function test_zero_amount_failure()
    {
        $request = $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => 0,
                'apy' => 30,
                'stacking_at' => (new DateTimeImmutable('-1 minute'))->format('Y-m-d H:i:s')
            ])->assertJsonValidationErrors(['amount']);
    }

    public function test_zero_apy_failure()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => 0.000001,
                'apy' => 0,
                'stacking_at' => (new DateTimeImmutable('-1 minute'))->format('Y-m-d H:i:s')
            ])
            ->assertJsonValidationErrors(['apy']);
    }

    public function test_string_amount_failure()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => 'error',
                'apy' => 30,
                'stacking_at' => (new DateTimeImmutable('-1 minute'))->format('Y-m-d H:i:s')
            ])
            ->assertJsonValidationErrors(['amount']);
    }

    public function test_string_apy_failure()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => 0.000001,
                'apy' => 'error',
                'stacking_at' => (new DateTimeImmutable('-1 minute'))->format('Y-m-d H:i:s')
            ])
            ->assertJsonValidationErrors(['apy']);
    }

    public function test_staking_date_future_failure()
    {
        $this->actingAs($this->user)
            ->postJson(route('api.portfolio.stacking.create'), [
                'user_id' => $this->user->getKey(),
                'portfolio_id' => $this->portfolio->getKey(),
                'coin_uuid' => $this->coin->getKey(),
                'amount' => 0.000001,
                'apy' => 30,
                'stacking_at' => (new DateTimeImmutable('+1 minute'))->format('Y-m-d H:i:s')
            ])
            ->assertJsonValidationErrors(['stacking_at']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = Portfolio::factory()->create(['user_id' => $this->user->getKey()]);
        $this->coin = Coin::factory()->create();

        Transaction::factory()->create([
            'user_id' => $this->user->getKey(),
            'portfolio_id' => $this->portfolio->getKey(),
            'coin_uuid' => $this->coin->getKey(),
        ]);
    }
}
