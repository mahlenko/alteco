<?php

use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function() {
    //
    Route::get('/', [\Blackshot\CoinMarketSdk\Controllers\Home::class, 'index'])->name('home');
//    Route::get('/', function() {
//        return redirect()->route('login');
//    });

//    Route::get('/sync', [\Blackshot\CoinMarketSdk\Controllers\SyncGetCources::class, 'index']);
    Route::post('/sync/webhook/payment', [\Blackshot\CoinMarketSdk\Controllers\SyncGetCources::class, 'webhook'])->name('webhook.payment');

    //
    Route::get('/subscribe', [\Blackshot\CoinMarketSdk\Controllers\Subscribe::class, 'index'])->name('subscribe');

    //
    Route::middleware('auth')->group(function() {
        //
        Route::prefix('coins')->name('coins.')->group(function() {
            Route::get('/', [\Blackshot\CoinMarketSdk\Controllers\Coins\Index::class, 'index'])->name('home');
            Route::get('/view/{uuid}', [\Blackshot\CoinMarketSdk\Controllers\Coins\View::class, 'index'])->name('view');
            Route::post('/filter/store', [\Blackshot\CoinMarketSdk\Controllers\Coins\SaveFilter::class, 'index'])->name('filter.store');

            /*  */
            Route::get('/edit/{coin}', [\Blackshot\CoinMarketSdk\Controllers\Coins\Edit::class, 'index'])
                ->middleware('admin')
                ->name('edit');

            Route::post('/store', [\Blackshot\CoinMarketSdk\Controllers\Coins\Store::class, 'index'])
                ->middleware('admin')
                ->name('store');
        });

        //
        Route::prefix('signals')->name('signals.')->group(function() {
            Route::get('/', [\Blackshot\CoinMarketSdk\Controllers\Signals\Index::class, 'index'])->name('home');
            Route::post('/filter/store', [\Blackshot\CoinMarketSdk\Controllers\Signals\SaveFilter::class, 'index'])->name('filter.store');
        });

        //
        Route::prefix('users')->name('users.')->group(function() {
            Route::get('/', [\Blackshot\CoinMarketSdk\Controllers\Users\Index::class, 'index'])->name('home');
            Route::post('/', [\Blackshot\CoinMarketSdk\Controllers\Users\Index::class, 'index'])->name('home');

            Route::get('/edit/{id?}', [\Blackshot\CoinMarketSdk\Controllers\Users\Edit::class, 'index'])->name('edit');
            Route::post('/store', [\Blackshot\CoinMarketSdk\Controllers\Users\Store::class, 'index'])->name('store');
            Route::post('/delete', [\Blackshot\CoinMarketSdk\Controllers\Users\Delete::class, 'index'])
                ->middleware('admin')
                ->name('delete');

            Route::post('/favorite', [\Blackshot\CoinMarketSdk\Controllers\Users\Favorite::class, 'index'])->name('favorite');
            Route::post('/tracking', [\Blackshot\CoinMarketSdk\Controllers\Users\Tracking::class, 'index'])->name('tracking');
            Route::post('/coin/buying', [\Blackshot\CoinMarketSdk\Controllers\Users\CoinBuying::class, 'index'])->name('coin.buying');
        });

        //
        Route::prefix('tariffs')->name('tariffs.')->middleware('admin')->group(function() {
            Route::get('/', [\Blackshot\CoinMarketSdk\Controllers\Tariffs\Home::class, 'index'])->name('home');
            Route::get('/edit/{tariff:id?}', [\Blackshot\CoinMarketSdk\Controllers\Tariffs\Edit::class, 'index'])->name('edit');
            Route::post('/store', [\Blackshot\CoinMarketSdk\Controllers\Tariffs\Edit::class, 'store'])->name('store');
            Route::delete('/delete', [\Blackshot\CoinMarketSdk\Controllers\Tariffs\Delete::class, 'index'])->name('delete');
        });

        //
        Route::prefix('banners')
            ->name('banners.')
            ->group(function() {
                Route::get('/', [\Blackshot\CoinMarketSdk\Controllers\Banner\Home::class, 'index'])->name('home');
                Route::get('edit/{banner?}', [\Blackshot\CoinMarketSdk\Controllers\Banner\Edit::class, 'index'])->name('edit');
                Route::post('store', [\Blackshot\CoinMarketSdk\Controllers\Banner\Edit::class, 'store'])->name('store');
                Route::post('delete', [\Blackshot\CoinMarketSdk\Controllers\Banner\Delete::class, 'index'])->name('delete');
            });

        //
        Route::prefix('settings')->name('settings.')->middleware('admin')->group(function() {
            Route::get('/', [\Blackshot\CoinMarketSdk\Controllers\Settings\Index::class, 'index'])->name('home');
            Route::post('/store', [\Blackshot\CoinMarketSdk\Controllers\Settings\Store::class, 'index'])->name('store');
        });
    });

});
