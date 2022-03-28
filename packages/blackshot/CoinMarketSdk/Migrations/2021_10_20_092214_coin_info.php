<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoinInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_info', function (Blueprint $table) {
            $table->uuid('coin_uuid')->primary();
            $table->string('category');
            $table->string('logo');
            $table->text('description');
            $table->text('notice');
            $table->timestamp('date_added');

            $table->timestamps();
        });

        Schema::table('coin_info', function (Blueprint $table) {
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
        Schema::dropIfExists('coin_info');
    }
}
