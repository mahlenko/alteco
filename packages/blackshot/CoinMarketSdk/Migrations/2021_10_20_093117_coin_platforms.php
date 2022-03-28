<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoinPlatforms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_platform_relations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('coin_uuid');
            $table->uuid('platform_uuid');
            $table->timestamps();

            $table->unique(['coin_uuid', 'platform_uuid']);
        });

        Schema::table('coin_platform_relations', function (Blueprint $table) {
            $table->foreign('platform_uuid')
                ->references('uuid')
                ->on('coin_platforms')
                ->onDelete('cascade');

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
        Schema::dropIfExists('coin_platform_relations');
    }
}
