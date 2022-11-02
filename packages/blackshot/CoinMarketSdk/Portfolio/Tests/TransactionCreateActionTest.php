<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Tests;

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Actions\Transaction\TransactionCreateAction;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Enums\TransferTypeEnum;
use Blackshot\CoinMarketSdk\Portfolio\Exceptions\TransactionException;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionCreateActionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Portfolio $portfolio;
    private Coin $coin;

    public function test_add()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25.3,
            'quantity' => 2,
            'fee' => 0,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy
        ]);

        $this->assertNotEmpty($transaction->getKey());
    }

    public function test_invalid_number()
    {
        $this->expectException(TransactionException::class);

        TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => -1200,
            'quantity' => 0,
            'fee' => -20,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy
        ]);
    }

    public function test_create_for_another_user()
    {
        $user = User::factory()->create();

        $this->expectException(TransactionException::class);

        TransactionCreateAction::handle($user, $this->portfolio, $this->coin, [
            'price' => 25.3,
            'quantity' => 2,
            'fee' => 0,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy
        ]);
    }

    public function test_create_for_another_user_by_admin()
    {
        $user = User::factory()->admin()->create();

        $transaction = TransactionCreateAction::handle($user, $this->portfolio, $this->coin, [
            'price' => 25.3,
            'quantity' => 2,
            'fee' => 0,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy
        ]);

        $this->assertNotEmpty($transaction->getKey());
    }

    public function test_quantity_result_greater_zero_for_type_buy()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25.3,
            'quantity' => 2,
            'fee' => 0,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy
        ]);

        $this->assertEquals(2, $transaction->quantity);
    }

    public function test_quantity_result_less_zero_for_type_sell()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25.3,
            'quantity' => 2,
            'fee' => 0,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Sell
        ]);

        $this->assertEquals(-2, $transaction->quantity);
    }

    public function test_quantity_result_greater_zero_for_type_transaction_in()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25.3,
            'quantity' => 2,
            'fee' => 0,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Transfer,
            'transfer_type' => TransferTypeEnum::In,
        ]);

        $this->assertEquals(2, $transaction->quantity);
    }

    public function test_quantity_result_less_zero_for_type_transaction_out()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25.3,
            'quantity' => 2,
            'fee' => 0,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Transfer,
            'transfer_type' => TransferTypeEnum::Out,
        ]);

        $this->assertEquals(-2, $transaction->quantity);
    }

    public function test_total_without_fee()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25,
            'quantity' => 2,
            'fee' => 0.5,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy,
        ]);

        $this->assertEquals(50, $transaction->total);
    }

    public function test_buy_type_total_with_fee()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25,
            'quantity' => 2,
            'fee' => 0.5,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Buy,
        ]);

        $this->assertEquals(50.5, $transaction->totalWithFee);
    }

    public function test_sell_type_total_with_fee()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25,
            'quantity' => 2,
            'fee' => 0.5,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Sell,
        ]);

        $this->assertEquals(-50.5, $transaction->totalWithFee);
    }

    public function test_transfer_in_total_with_fee()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25,
            'quantity' => 2,
            'fee' => 0.5,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Transfer,
            'transfer_type' => TransferTypeEnum::In
        ]);

        $this->assertEquals(50.5, $transaction->totalWithFee);
    }

    public function test_transfer_out_total_with_fee()
    {
        $transaction = TransactionCreateAction::handle($this->user, $this->portfolio, $this->coin, [
            'price' => 25,
            'quantity' => 2,
            'fee' => 0.5,
            'date_at' => new DateTimeImmutable(),
            'type' => TransactionTypeEnum::Transfer,
            'transfer_type' => TransferTypeEnum::Out
        ]);

        $this->assertEquals(-50.5, $transaction->totalWithFee);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = Portfolio::factory()->create(['user_id' => $this->user]);
        $this->coin = Coin::factory()->create();
    }
}
