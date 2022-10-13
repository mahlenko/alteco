<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoinCategoryMarket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_category_markets', function (Blueprint $table) {
            $table->uuid('category_uuid')->index();
            $table->double('cap', 30, 15);
            $table->double('cap_change', 30, 15);
            $table->timestamps();
        });

        Schema::table('coin_category_markets', function (Blueprint $table) {
            $table->foreign('category_uuid')
                ->references('uuid')
                ->on('coin_categories')
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
        Schema::dropIfExists('coin_category_markets');
    }
}
