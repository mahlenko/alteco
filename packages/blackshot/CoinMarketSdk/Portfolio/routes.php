<?php

use Blackshot\CoinMarketSdk\Portfolio\Controllers\api\ApiChartsController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\api\ApiPortfolioController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\api\ApiStackingController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\api\ApiTransactionController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\DataController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\PortfolioController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\StackingController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/**
 * Page routes
 */
Route::prefix('portfolio')->name('portfolio.')->middleware(['web', 'auth:web'])->group(function() {
    Route::get('/create', [PortfolioController::class, 'create'])->name('create');
    Route::get('/{portfolio:id?}', [PortfolioController::class, 'index'])->name('home');

    Route::prefix('data')->name('data.')->group(function() {
        Route::get('price', [DataController::class, 'getPriceCoin'])->name('price');
    });

    Route::prefix('transaction')->name('transaction.')->middleware(['web', 'auth:web'])->group(function() {
        Route::get('/create/{portfolio}', [TransactionController::class, 'create'])->name('create');
        Route::get('/{portfolio}/{coin}', [TransactionController::class, 'index'])->name('home');
    });

    Route::prefix('stacking')->name('stacking.')->group(function() {
        Route::get('/{portfolio}/{coin}', [StackingController::class, 'index'])->name('home');
        Route::get('/create/{portfolio}/{coin}', [StackingController::class, 'create'])->name('create');
    });
});

/**
 * API requests
 */
Route::prefix('v1')
    ->middleware(['api', 'auth:sanctum'])
//    ->middleware(['web', 'auth:web'])
    ->name('api.')
    ->group(function() {
        Route::prefix('portfolio')->name('portfolio.')->group(function() {
            /* portfolio */
            Route::post('create', [ApiPortfolioController::class, 'create'])->name('create');
            Route::put('update', [ApiPortfolioController::class, 'update'])->name('update');
            Route::delete('delete', [ApiPortfolioController::class, 'delete'])->name('delete');

            Route::get('charts', [ApiChartsController::class, 'portfolio'])->name('charts');

            // portfolio/transaction
            Route::prefix('transactions')->name('transaction.')->group(function() {
                Route::post('create', [ApiTransactionController::class, 'create'])->name('create');
                Route::put('update', [ApiTransactionController::class, 'update'])->name('update');
                Route::delete('delete', [ApiTransactionController::class, 'delete'])->name('delete');
            });

            // portfolio/stacking
            Route::prefix('stacking')->name('stacking.')->group(function() {
                Route::post('create', [ApiStackingController::class, 'create'])->name('create');
                Route::delete('delete', [ApiStackingController::class, 'delete'])->name('delete');
            });
        });
});
