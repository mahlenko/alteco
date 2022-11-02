<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Stacking\StakingCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\PortfolioException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Stacking;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StackingCreateActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Portfolio $portfolio;
    private Coin $coin;

    public function test_create()
    {
        $quantity = $this->portfolio->items()->findCoin($this->coin)->quantity();

        $stacking = StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, $data = [
            'amount' => $quantity - ($quantity * 0.1),
            'apy' => 4,
            'stacking_at' => new DateTimeImmutable()
        ]);

        $this->assertDatabaseCount(Stacking::class, 1);
        $this->assertEquals($stacking->amount, $data['amount']);
        $this->assertEquals($stacking->apy, $data['apy']);
        $this->assertEquals($stacking->stacking_at->format('Y-m-d'), $data['stacking_at']->format('Y-m-d'));
        $this->assertEquals($stacking->user_id, $this->portfolio->user_id);
        $this->assertEquals($stacking->portfolio_id, $this->portfolio->getKey());
    }

    public function test_very_small_amount_success()
    {
        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'amount' => 0.000000001,
            'apy' => 4,
            'stacking_at' => new DateTimeImmutable()
        ]);

        $this->assertDatabaseCount(Stacking::class, 1);
    }

    public function test_more_than_available_failure()
    {
        $this->expectException(PortfolioException::class);
        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'amount' => $this->portfolio->items()->findCoin($this->coin)->quantity() + 1,
            'apy' => 4,
            'stacking_at' => new DateTimeImmutable()
        ]);
    }

//    public function test_negative_amount_failure()
//    {
//        $this->expectException(PortfolioException::class);
//        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
//            'amount' => -1,
//            'apy' => 4,
//            'stacking_at' => new DateTimeImmutable()
//        ]);
//    }

//    public function test_negative_apy_failure()
//    {
//        $this->expectException(PortfolioException::class);
//        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
//            'amount' => $this->portfolio->items()->findCoin($this->coin)->quantity() - 1,
//            'apy' => -1,
//            'stacking_at' => new DateTimeImmutable()
//        ]);
//    }

//    public function test_zero_amount_failure()
//    {
//        $this->expectException(PortfolioException::class);
//        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
//            'amount' => 0,
//            'apy' => 4,
//            'stacking_at' => new DateTimeImmutable()
//        ]);
//    }

//    public function test_zero_apy_failure()
//    {
//        $this->expectException(PortfolioException::class);
//        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
//            'amount' => 0.0000001,
//            'apy' => 0,
//            'stacking_at' => new DateTimeImmutable()
//        ]);
//    }

    public function test_string_amount_failure()
    {
        $this->expectException(PortfolioException::class);
        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'amount' => 'error',
            'apy' => 1,
            'stacking_at' => new DateTimeImmutable()
        ]);
    }

//    public function test_string_apy_failure()
//    {
//        $this->expectException(PortfolioException::class);
//        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
//            'amount' => 0.0000001,
//            'apy' => 'error',
//            'stacking_at' => new DateTimeImmutable()
//        ]);
//    }

//    public function test_staking_date_future_failure()
//    {
//        $this->expectException(PortfolioException::class);
//        StakingCreateAction::handle($this->user, $this->portfolio, $this->coin, [
//            'amount' => 0.0000001,
//            'apy' => 'error',
//            'stacking_at' => new DateTimeImmutable('+1 day')
//        ]);
//    }

    public function test_another_user_failure()
    {
        $another = User::factory()->create();

        $this->expectException(PortfolioException::class);
        StakingCreateAction::handle($another, $this->portfolio, $this->coin, [
            'amount' => 0.0000001,
            'apy' => 3,
            'stacking_at' => new DateTimeImmutable()
        ]);
    }

    public function test_admin_success()
    {
        $admin = User::factory()->admin()->create();

        StakingCreateAction::handle($admin, $this->portfolio, $this->coin, [
            'amount' => 0.0000001,
            'apy' => 3,
            'stacking_at' => new DateTimeImmutable()
        ]);

        $this->assertDatabaseCount(Stacking::class, 1);
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
