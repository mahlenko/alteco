<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTariffBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tariff_banners', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->unsignedBigInteger('tariff_id');
            $table->text('body')->nullable();
            $table->string('picture', 255)->nullable();
            $table->date('start');
            $table->date('end')->nullable();
            $table->boolean('is_active')->index();
            $table->bigInteger('views');
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->timestamps();

            $table->foreign('tariff_id')
                ->on('tariffs')
                ->references('id')
                ->cascadeOnDelete();

            $table->foreign('created_user_id')
                ->on('users')
                ->references('id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tariff_banners');
    }
}
