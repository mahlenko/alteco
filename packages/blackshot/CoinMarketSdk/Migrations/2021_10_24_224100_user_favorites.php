<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserFavorites extends \Illuminate\Database\Migrations\Migration
{

    public function up()
    {
        Schema::create('user_favorites', function(Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->uuid('coin_uuid');
            $table->timestamps();
        });

        Schema::table('user_favorites', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_favorites');
    }

}
