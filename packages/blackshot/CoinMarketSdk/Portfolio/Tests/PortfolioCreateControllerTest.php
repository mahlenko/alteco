<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioCreateControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthorized()
    {
        $this
            ->postJson(route('api.portfolio.add'))
            ->assertUnauthorized();
    }

    public function test_validation()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('api.portfolio.add'));

        $response->assertJsonValidationErrors(['user_id', 'name']);
    }

    public function test_success_create()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('api.portfolio.add'), [
                'user_id' => $user->getKey(),
                'name' => 'My portfolio'
            ])->assertOk();

        $this->assertDatabaseCount(Portfolio::class, 1);

        $this->assertTrue($response->json('ok'));
        $this->assertTrue($response->json('data.name') === 'My portfolio');
    }

    public function test_failure_create()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson(route('api.portfolio.add'), [
                'user_id' => $user2->getKey(),
                'name' => 'My portfolio'
            ])->assertOk();

        $this->assertFalse($response->json('ok'));
        $this->assertNotEmpty($response->json('message'));
    }

}
