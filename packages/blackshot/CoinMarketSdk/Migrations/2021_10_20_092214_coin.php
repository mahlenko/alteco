<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Coin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->bigInteger('id')->index();
            $table->bigInteger('rank')->default(0);
            $table->string('name');
            $table->string('symbol');
            $table->string('slug');
            $table->boolean('is_active')->default(0);
            $table->timestamp('first_historical_data')->nullable();
            $table->timestamp('last_historical_data')->nullable();

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
        Schema::dropIfExists('coins');
    }
}
