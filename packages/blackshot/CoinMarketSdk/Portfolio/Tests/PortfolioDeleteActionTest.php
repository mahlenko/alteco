<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Portfolio\PortfolioDeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortfolioDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Portfolio $portfolio;

    public function test_delete()
    {
        PortfolioDeleteAction::handle($this->user, $this->portfolio->getKey());
        $this->assertDatabaseCount(Portfolio::class, 0);
    }

    public function test_delete_for_another_user()
    {
        $user = User::factory()->create();

        $this->expectException(PortfolioException::class);
        PortfolioDeleteAction::handle($user, $this->portfolio->getKey());

        $this->assertDatabaseCount(Portfolio::class, 1);
    }

    public function test_create_for_another_user_by_admin()
    {
        $admin = User::factory()->admin()->create();

        PortfolioDeleteAction::handle($admin, $this->portfolio->getKey());
        $this->assertDatabaseCount(Portfolio::class, 0);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->portfolio = Portfolio::factory()->create([
            'user_id' => $this->user->getKey()
        ]);
    }
}
