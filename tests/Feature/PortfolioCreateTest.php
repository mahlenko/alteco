<?php

namespace Tests\Feature;

use App\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\CreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PortfolioCreateTest extends TestCase
{
    private User $user;
    private Portfolio $portfolio;

    use RefreshDatabase;

    public function test_create()
    {
        $portfolio = CreateAction::handle($this->user, 'default');
        $this->assertDatabaseCount(Portfolio::class, 1);
        $this->assertEquals($this->user->id, $portfolio->user_id);
    }

    public function test_ucfirst_name()
    {
        $portfolio = CreateAction::handle($this->user, 'default');
        $this->assertEquals('Default', $portfolio->name);
    }

    public function test_limit_max_portfolios()
    {
        $this->expectExceptionCode(613);

        CreateAction::handle($this->user, 'First');
        CreateAction::handle($this->user, 'Two');
        CreateAction::handle($this->user, 'Three');

        $this->assertDatabaseCount(Portfolio::class, 2);
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
