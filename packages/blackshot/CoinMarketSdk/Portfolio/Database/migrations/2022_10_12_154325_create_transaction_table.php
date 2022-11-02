<?php

use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_transaction', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignIdFor(User::class)->constrained()->index();
            $table->foreignIdFor(Portfolio::class)->constrained();
            $table->foreignIdFor(Coin::class)->constrained(column: 'uuid');
            $table->double('price')->unsigned();
            $table->double('quantity');
            $table->double('fee')->unsigned()->default(0.0);
            $table->timestamp('date_at');
            $table->string('type');
            $table->string('transfer_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_assets');
    }
}
