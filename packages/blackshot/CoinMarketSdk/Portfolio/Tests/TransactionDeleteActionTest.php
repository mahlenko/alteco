<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction\TransactionDeleteAction;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\TransactionException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Portfolio $portfolio;
    private Transaction $transaction;

    public function test_delete()
    {
        TransactionDeleteAction::handle($this->user, $this->transaction);
        $this->assertDatabaseCount(Transaction::class, 0);
    }

    public function test_delete_for_another_user()
    {
        $user = User::factory()->create();

        $this->expectException(TransactionException::class);
        TransactionDeleteAction::handle($user, $this->transaction);

        $this->assertDatabaseCount(Transaction::class, 1);
    }

    public function test_delete_for_another_user_by_admin()
    {
        $user = User::factory()->admin()->create();

        TransactionDeleteAction::handle($user, $this->transaction);
        $this->assertDatabaseCount(Transaction::class, 0);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->portfolio = Portfolio::factory()->create([
            'user_id' => $this->user->getKey()
        ]);

        $this->transaction = Transaction::factory()->create([
            'user_id' => $this->user->getKey(),
            'portfolio_id' => $this->portfolio->getKey()
        ]);
    }
}
