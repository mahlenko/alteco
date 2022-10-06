<?php

use Blackshot\CoinMarketSdk\Portfolio\Controllers\PortfolioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('auth:sanctum')->name('api.')->group(function() {
    Route::prefix('portfolio')->name('portfolio.')->group(function() {
        Route::get('/', [PortfolioController::class, 'index'])->name('home');
        Route::post('store/{portfolio?}', [PortfolioController::class, 'store'])->name('store');
        Route::delete('delete', [PortfolioController::class, 'delete'])->name('delete');
    });
});
