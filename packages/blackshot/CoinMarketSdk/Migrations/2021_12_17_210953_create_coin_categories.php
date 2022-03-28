<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoinCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coin_categories', function (Blueprint $table) {
            $table->id();
            $table->uuid('coin_uuid');
            $table->uuid('category_uuid');
            $table->timestamps();

            $table->unique(['coin_uuid', 'category_uuid']);
        });

        Schema::table('coin_categories', function (Blueprint $table) {
            $table->foreign('coin_uuid')
                ->references('uuid')
                ->on('coins')
                ->onDelete('cascade');

            $table->foreign('category_uuid')
                ->references('uuid')
                ->on('categories')
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
        Schema::dropIfExists('coin_categories');
    }
}
