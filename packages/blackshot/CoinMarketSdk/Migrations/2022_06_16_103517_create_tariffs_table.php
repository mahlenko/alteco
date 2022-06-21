<?php

use Blackshot\CoinMarketSdk\Repositories\TariffRepository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->double('amount')->default(0);
            $table->integer('days')->default(0);
            $table->boolean('free')->default(false);
            $table->boolean('default')->default(false);
            $table->timestamps();
        });

        TariffRepository::create('Free', 0, 3, true, true);
        TariffRepository::create('Платный', 5000, 365);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariffs');
    }
}
