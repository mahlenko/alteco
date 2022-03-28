<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoinCategoriesRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coin_categories', function (Blueprint $table) {
            $table->rename('categories');
        });

        Schema::table('coin_category_markets', function (Blueprint $table) {
            $table->rename('category_markets');
        });

        Schema::table('coin_category_volumes', function (Blueprint $table) {
            $table->rename('category_volumes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->rename('coin_categories');
        });

        Schema::table('category_markets', function (Blueprint $table) {
            $table->rename('coin_category_markets');
        });

        Schema::table('category_volumes', function (Blueprint $table) {
            $table->rename('coin_category_volumes');
        });
    }
}
