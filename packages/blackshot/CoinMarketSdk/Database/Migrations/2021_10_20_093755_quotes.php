<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Quotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_quotes', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->uuid('coin_uuid')->index();
            $table->string('currency');
            $table->bigInteger('cmc_rank')->nullable();
            $table->bigInteger('max_supply')->nullable();
            $table->bigInteger('circulating_supply')->nullable();
            $table->bigInteger('total_supply')->nullable();
            $table->double('price', 50, 25)->default(0);
            $table->double('volume_24h', 50, 25)->default(0);
            $table->double('volume_24h_reported', 50, 25)->default(0);
            $table->double('volume_7d', 50, 25)->default(0);
            $table->double('volume_7d_reported', 50, 25)->default(0);
            $table->double('volume_30d', 50, 25)->default(0);
            $table->double('volume_30d_reported', 50, 25)->default(0);
            $table->double('volume_change_24h', 50, 25)->default(0);
            $table->double('percent_change_1h', 50, 25)->default(0);
            $table->double('percent_change_24h', 50, 25)->default(0);
            $table->double('percent_change_7d', 50, 25)->default(0);
            $table->double('percent_change_30d', 50, 25)->default(0);
            $table->double('percent_change_60d', 50, 25)->default(0);
            $table->double('percent_change_90d', 50, 25)->default(0);
            $table->double('market_cap', 50, 25)->default(0);
            $table->double('market_cap_dominance', 50, 25)->default(0);
            $table->double('fully_diluted_market_cap', 50, 25)->default(0);
            $table->double('market_cap_by_total_supply', 50, 25)->default(0);
            $table->timestamp('last_updated');
            $table->timestamps();
        });

        Schema::table('coin_quotes', function (Blueprint $table) {
            $table->foreign('coin_uuid')
                ->references('uuid')
                ->on('coins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coin_quotes');
    }
}
