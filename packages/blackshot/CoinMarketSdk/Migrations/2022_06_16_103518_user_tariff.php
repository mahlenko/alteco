<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserTariff extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('tariff_id')
                ->unsigned()
                ->nullable()
                ->default(1)
                ->after('remember_token');

            $table->foreign('tariff_id')
                ->references('id')
                ->on('tariffs')
                ->nullOnDelete();
        });

        \Illuminate\Support\Facades\DB::table('users')
            ->update(['tariff_id' => 2]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_tariff_id_foreign');
            $table->dropColumn('tariff_id');
        });
    }
}
