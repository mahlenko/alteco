<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\StoreAction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioCreateTest extends TestCase
{
    private User $user;
    private Portfolio $portfolio;

    use RefreshDatabase;

    public function test_create()
    {
        $portfolio = StoreAction::handle($this->user, 'default');
        $this->assertDatabaseCount(Portfolio::class, 1);
        $this->assertEquals($this->user->id, $portfolio->user_id);
    }

    public function test_ucfirst_name()
    {
        $portfolio = StoreAction::handle($this->user, 'default');
        $this->assertEquals('Default', $portfolio->name);
    }

    public function test_limit_max_portfolios()
    {
        $this->expectExceptionCode(603);
        StoreAction::handle($this->user, 'First');
        StoreAction::handle($this->user, 'Two');
        StoreAction::handle($this->user, 'Three'); // send exceptions
        StoreAction::handle($this->user, 'Four');
        StoreAction::handle($this->user, 'Five');

        $this->assertDatabaseCount(Portfolio::class, config('portfolio.max_portfolios'));
        $this->assertEquals('Two', Portfolio::all()->last()->name);
    }

    public function test_store_validation_request()
    {
        $this
            ->actingAs($this->user)
            ->postJson(route('api.portfolio.store'), [])
            ->assertStatus(422);
    }

    public function test_unauthorized_user()
    {
        $this->postJson(route('api.portfolio.store'))
            ->assertUnauthorized();
    }

    public function test_create_request()
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson(route('api.portfolio.store'), [
                'name' => 'test'
            ]);

        $response->assertOk();

        $this->assertTrue($response->json('ok'));
        $this->assertArrayHasKey('id', $response->json('data'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
}
