<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('signals', function(Blueprint $table) {
            $table->dropIndex(['date']);
            $table->index(['coin_uuid', 'date']);
        });

        Schema::table('coin_quotes', function(Blueprint $table) {
            $table->dropIndex(['last_updated']);
            $table->index(['coin_uuid', 'last_updated']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('signals', function(Blueprint $table) {
            $table->dropIndex(['coin_uuid_date']);
            $table->index(['date']);
        });

        Schema::table('coin_quotes', function(Blueprint $table) {
            $table->dropIndex(['coin_uuid', 'last_updated']);
            $table->index(['last_updated']);
        });
    }
}
