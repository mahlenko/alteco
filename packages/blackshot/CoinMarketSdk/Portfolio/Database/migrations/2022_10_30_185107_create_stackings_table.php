<?php

use Blackshot\CoinMarketSdk\Models\User;
use Blackshot\CoinMarketSdk\Portfolio\Models\Portfolio;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portfolio_stackings', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Portfolio::class)->constrained();
            $table->foreignIdFor(\Blackshot\CoinMarketSdk\Models\Coin::class)->constrained(column: 'uuid');
            $table->double('amount')->unsigned();
            $table->float('apy')->unsigned();
            $table->timestamp('stacking_at');
            $table->timestamps();

            $table->index(['user_id', 'portfolio_id']);
            $table->index(['coin_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portfolio_stackings');
    }
}
