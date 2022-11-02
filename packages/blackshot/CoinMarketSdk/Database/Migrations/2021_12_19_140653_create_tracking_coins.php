<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackingCoins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_coins', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->uuid('coin_uuid');
            $table->timestamps();

            $table->unique(['user_id', 'coin_uuid']);
        });

        Schema::table('tracking_coins', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('coin_uuid')
                ->references('uuid')
                ->on('coins')
                ->onDelete('cascade');
        });

        //
        $favorites = \Blackshot\CoinMarketSdk\Models\UserFavorites::all();
        foreach ($favorites as $favorite) {
            $tracking = \Blackshot\CoinMarketSdk\Models\TrackingCoin::where([
                'user_id' => $favorite->user_id,
                'coin_uuid' => $favorite->coin_uuid
            ])->count();

            if ($tracking) {
                continue;
            }

            $track = new \Blackshot\CoinMarketSdk\Models\TrackingCoin();
            $track->user_id = $favorite->user_id;
            $track->coin_uuid = $favorite->coin_uuid;
            $track->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tracking_coins');
    }
}
