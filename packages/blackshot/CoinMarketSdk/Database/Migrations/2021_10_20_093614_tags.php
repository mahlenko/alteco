<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tags extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('coin_uuid');
            $table->string('name')->index();
            $table->timestamps();
        });

        Schema::table('coin_tags', function (Blueprint $table) {
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
        Schema::dropIfExists('coin_tags');
    }
}
