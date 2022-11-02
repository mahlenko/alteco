<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioDeleteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized()
    {
        $this
            ->deleteJson(route('api.portfolio.delete'))
            ->assertUnauthorized();
    }

    public function test_validation()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->deleteJson(route('api.portfolio.delete'));

        $response->assertJsonValidationErrors(['id']);
    }

    public function test_success_delete()
    {
        $user = User::factory()->create();
        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->getKey()
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson(route('api.portfolio.delete'), [
                'id' => $portfolio->getKey()
            ])->assertOk();

        $this->assertDatabaseCount(Portfolio::class, 0);

        $this->assertTrue($response->json('ok'));
        $this->assertTrue($response->json('data.id') === $portfolio->getKey());
    }

    public function test_failure_delete()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $portfolio = Portfolio::factory()->create([
            'user_id' => $user->getKey()
        ]);

        $response = $this
            ->actingAs($user2)
            ->deleteJson(route('api.portfolio.delete'), [
                'id' => $portfolio->getKey()
            ])->assertOk();

        $this->assertDatabaseCount(Portfolio::class, 1);

        $this->assertFalse($response->json('ok'));
        $this->assertNotEmpty($response->json('message'));
    }

}
