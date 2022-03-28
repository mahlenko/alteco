<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoinCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_categories', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->integer('num_tokens')->unsigned()->nullable();
            $table->double('avg_price_change', 20, 15);
            $table->timestamp('last_updated');

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
        Schema::dropIfExists('coin_categories');
    }
}
