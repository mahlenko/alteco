<?php

use Blackshot\CoinMarketSdk\Portfolio\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api', 'auth:sanctum'])->name('api.')->group(function() {
    Route::prefix('portfolio')->name('portfolio.')->group(function() {
        Route::get('/', [PortfolioController::class, 'index'])->name('home');
        Route::post('store/{portfolio?}', [PortfolioController::class, 'store'])->name('store');
        Route::delete('delete', [PortfolioController::class, 'delete'])->name('delete');

        // Assets
        Route::prefix('assets')->name('assets.')->group(function() {

        });
    });
});
