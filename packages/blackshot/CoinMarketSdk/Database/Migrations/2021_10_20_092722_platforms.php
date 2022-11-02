<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Platforms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_platforms', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->bigInteger('id');
            $table->string('name');
            $table->string('symbol');
            $table->string('slug');
            $table->string('token_address');
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
        Schema::dropIfExists('coin_platforms');
    }
}
