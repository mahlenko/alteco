<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction\TransactionUpdateAction;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransferTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Models\Transaction;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionUpdateActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Transaction $transaction;

    public function test_update()
    {
        $transaction = TransactionUpdateAction::handle($this->user, $this->transaction, [
            'fee' => 2
        ]);

        $this->assertDatabaseCount(Transaction::class, 1);
        $this->assertEquals(2, $transaction->fee);
    }

    public function test_update_type_buy()
    {
        $transaction = TransactionUpdateAction::handle($this->user, $this->transaction, [
            'type' => TransactionTypeEnum::Buy
        ]);

        $this->assertDatabaseCount(Transaction::class, 1);

        $this->assertEquals(TransactionTypeEnum::Buy, $transaction->type);
        $this->assertNull($transaction->transfer_type);
        $this->assertGreaterThan(0, $transaction->quantity);
    }

    public function test_update_type_sell()
    {
        $transaction = TransactionUpdateAction::handle($this->user, $this->transaction, [
            'type' => TransactionTypeEnum::Sell
        ]);

        $this->assertDatabaseCount(Transaction::class, 1);
        $this->assertEquals(TransactionTypeEnum::Sell, $transaction->type);
        $this->assertNull($transaction->transfer_type);
        $this->assertLessThan(0, $transaction->quantity);
    }

    public function test_update_type_transfer_in()
    {
        $transaction = TransactionUpdateAction::handle($this->user, $this->transaction, [
            'type' => TransactionTypeEnum::Transfer,
            'transfer_type' => TransferTypeEnum::In
        ]);

        $this->assertDatabaseCount(Transaction::class, 1);
        $this->assertGreaterThan(0, $transaction->quantity);
        $this->assertEquals(TransactionTypeEnum::Transfer, $transaction->type);
        $this->assertEquals(TransferTypeEnum::In, $transaction->transfer_type);
    }

    public function test_update_type_transfer_out()
    {
        $transaction = TransactionUpdateAction::handle($this->user, $this->transaction, [
            'type' => TransactionTypeEnum::Transfer,
            'transfer_type' => TransferTypeEnum::Out
        ]);

        $this->assertDatabaseCount(Transaction::class, 1);
        $this->assertLessThan(0, $transaction->quantity);
        $this->assertEquals(TransactionTypeEnum::Transfer, $transaction->type);
        $this->assertEquals(TransferTypeEnum::Out, $transaction->transfer_type);
    }

    public function test_update_transfer_type_nullable_exception()
    {
        $this->expectException(TransferException::class);

        TransactionUpdateAction::handle($this->user, $this->transaction, [
            'type' => TransactionTypeEnum::Transfer,
        ]);
    }

    public function test_update_transfer_in_type_to_sell()
    {
        $transaction = Transaction::factory()->transferIn()->create([
            'user_id' => $this->user->getKey()
        ]);

        $transaction_update = TransactionUpdateAction::handle($this->user, $transaction, [
            'type' => TransactionTypeEnum::Sell,
        ]);

        $this->assertEquals(TransactionTypeEnum::Sell, $transaction_update->type);
        $this->assertLessThan(0, $transaction_update->quantity);
        $this->assertNull($transaction_update->transfer_type);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->transaction = Transaction::factory()->create([
            'user_id' => $this->user->getKey()
        ]);
    }
}
