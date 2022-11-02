<?php

use Blackshot\CoinMarketSdk\Portfolio\Controllers\TransactionController;
use Blackshot\CoinMarketSdk\Portfolio\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['api', 'auth:sanctum'])->name('api.')->group(function() {
    Route::prefix('portfolio')->name('portfolio.')->group(function() {
        Route::get('/', [PortfolioController::class, 'index'])->name('home');
        Route::post('add', [PortfolioController::class, 'add'])->name('add');
        Route::put('update', [PortfolioController::class, 'update'])->name('update');
        Route::delete('delete', [PortfolioController::class, 'delete'])->name('delete');

        // Assets
        Route::prefix('transaction')->name('transaction.')->group(function() {
            Route::post('create', [TransactionController::class, 'create'])->name('create');
            Route::put('update', [TransactionController::class, 'update'])->name('update');
            Route::delete('delete', [TransactionController::class, 'delete'])->name('delete');
        });
    });
});
