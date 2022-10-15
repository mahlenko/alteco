<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioUpdateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized()
    {
        $this
            ->putJson(route('api.portfolio.update'))
            ->assertUnauthorized();
    }

    public function test_validation()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->putJson(route('api.portfolio.update'));

        $response->assertJsonValidationErrors(['portfolio_id', 'name']);
    }

    public function test_success_update()
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->getKey()
        ]);

        $response = $this->actingAs($user)
            ->putJson(route('api.portfolio.update'), [
                'portfolio_id' => $portfolio->getKey(),
                'name' => 'new name'
            ])->assertOk();

        $this->assertDatabaseCount(Portfolio::class, 1);
        $this->assertTrue($response->json('data.name') === 'New name');
    }

    public function test_failure_update()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->getKey()
        ]);

        $response = $this->actingAs($user2)
            ->putJson(route('api.portfolio.update'), [
                'portfolio_id' => $portfolio->getKey(),
                'name' => 'new name'
            ])->assertOk();

        $this->assertFalse($response->json('ok'));
        $this->assertNotEmpty($response->json('message'));
    }

}
