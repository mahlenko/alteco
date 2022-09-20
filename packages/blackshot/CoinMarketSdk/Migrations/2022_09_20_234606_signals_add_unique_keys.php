<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SignalsAddUniqueKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signals', function (Blueprint $table) {
            $table->dropIndex(['coin_uuid', 'date']);
            $table->unique(['coin_uuid', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signals', function (Blueprint $table) {
            $table->dropUnique(['coin_uuid', 'date']);
            $table->index(['coin_uuid', 'date']);
        });
    }
}
