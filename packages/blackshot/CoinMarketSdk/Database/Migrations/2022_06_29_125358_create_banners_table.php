<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->uuid('uuid');
            $table->string('picture', 255)->nullable();
            $table->text('title')->nullable();
            $table->text('body')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->date('start');
            $table->date('end')->nullable();
            $table->enum('type', [array_column(\Blackshot\CoinMarketSdk\Enums\BannerTypes::cases(), 'name')])->index();
            $table->unsignedInteger('delay_seconds', false)->default(0);
            $table->unsignedInteger('not_disturb_hours', false)->default(0);
            $table->boolean('is_active')->index();
            $table->bigInteger('views');
            $table->unsignedBigInteger('created_user_id')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('banners');
    }
}
