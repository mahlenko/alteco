<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CoinsRankPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->bigInteger('rank_30d', false)
                ->nullable()
                ->after('rank');

            $table->bigInteger('rank_60d', false)
                ->nullable()
                ->after('rank_30d');

            $table->double('price', 50, 25)
                ->nullable()
                ->after('rank_60d');

            $table->double('percent_change_1h', 50, 25)
                ->nullable()
                ->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coins', function (Blueprint $table) {
            $table->dropColumn('rank_30d');
            $table->dropColumn('rank_60d');
            $table->dropColumn('price');
            $table->dropColumn('percent_change_1h');
        });
    }
}
