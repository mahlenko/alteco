<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioUpdateAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PortfolioUpdateActionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;
    private Portfolio $portfolio;

    public function test_update()
    {
        $portfolio = PortfolioUpdateAction::handle($this->user, $this->portfolio, [
            'name' => 'New name'
        ]);

        $this->assertDatabaseCount(Portfolio::class, 1);
        $this->assertEquals('New name', $portfolio->name);
    }

    public function test_update_for_another_user()
    {
        $user = User::factory()->create();

        $this->expectException(PortfolioException::class);

        PortfolioUpdateAction::handle($user, $this->portfolio, [
            'name' => 'New name'
        ]);
    }

    public function test_create_for_another_user_by_admin()
    {
        $admin = User::factory()->admin()->create();

        $portfolio = PortfolioUpdateAction::handle($admin, $this->portfolio, [
            'name' => 'New name'
        ]);

        $this->assertDatabaseCount(Portfolio::class, 1);
        $this->assertEquals('New name', $portfolio->name);
    }

    public function test_empty_name()
    {
        $this->expectException(PortfolioException::class);

        PortfolioUpdateAction::handle($this->user, $this->portfolio, [
            'name' => ''
        ]);
    }

    public function test_cannot_replace_user()
    {
        $user = User::factory()->create();

        $portfolio = PortfolioUpdateAction::handle($this->user, $this->portfolio, [
            'user_id' => $user->getKey(),
            'name' => 'New name'
        ]);

        $this->assertFalse($user->getKey() == $portfolio['user_id']);
    }

    public function test_portfolio_ucfirst_name()
    {
        $portfolio = PortfolioUpdateAction::handle($this->user, $this->portfolio, [
            'name' => 'new name'
        ]);

        $this->assertEquals('New name', $portfolio->name);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = PortfolioCreateAction::handle($this->user, [
            'user_id' => $this->user->getKey(),
            'name' => $this->faker->sentence(2)
        ]);
    }
}
