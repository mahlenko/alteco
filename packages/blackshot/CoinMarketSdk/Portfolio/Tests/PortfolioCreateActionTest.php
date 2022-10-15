<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioCreateActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function test_create()
    {
        $portfolio = PortfolioCreateAction::handle($this->user, [
            'user_id' => $this->user->getKey(),
            'name' => 'default'
        ]);

        $this->assertDatabaseCount(Portfolio::class, 1);

        $this->assertEquals($this->user->id, $portfolio->user_id);
    }

    public function test_create_for_another_user()
    {
        $user = User::factory()->create();

        $this->expectException(PortfolioException::class);

        PortfolioCreateAction::handle($user, [
            'user_id' => $this->user->getKey(),
            'name' => 'default'
        ]);
    }

    public function test_create_for_another_user_by_admin()
    {
        $admin = User::factory()->admin()->create();

        $portfolio = PortfolioCreateAction::handle($admin, [
            'user_id' => $this->user->getKey(),
            'name' => 'default'
        ]);

        $this->assertDatabaseCount(Portfolio::class, 1);
        $this->assertEquals($this->user->getKey(), $portfolio->user_id);
    }

    public function test_limit_the_maximum_portfolios()
    {
        $this->expectException(PortfolioException::class);

        $max_portfolios = config('portfolio.max_portfolios');

        for($i = 0; $i <= $max_portfolios; $i++) {
            PortfolioCreateAction::handle($this->user, [
                'user_id' => $this->user->getKey(),
                'name' => 'Portfolio #' . $i
            ]);
        }

        $this->assertDatabaseCount(Portfolio::class, $max_portfolios);
    }

    public function test_empty_name()
    {
        $this->expectException(PortfolioException::class);

        PortfolioCreateAction::handle($this->user, [
            'user_id' => $this->user->getKey(),
            'name' => ''
        ]);
    }

    public function test_portfolio_ucfirst_name()
    {
        $portfolio = PortfolioCreateAction::handle($this->user, [
            'user_id' => $this->user->getKey(),
            'name' => 'default'
        ]);

        $this->assertEquals('Default', $portfolio->name);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }
}
