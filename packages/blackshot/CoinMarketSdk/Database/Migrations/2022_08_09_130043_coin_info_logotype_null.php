<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoinInfoLogotypeNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coin_info', function (Blueprint $table) {
            $table->string('logo')->nullable()->change();
            $table->text('description')->nullable()->change();
            $table->text('notice')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coin_info', function (Blueprint $table) {
            $table->string('logo')->nullable(false)->change();
            $table->text('description')->nullable(false)->change();
            $table->text('notice')->nullable(false)->change();
        });
    }
}
