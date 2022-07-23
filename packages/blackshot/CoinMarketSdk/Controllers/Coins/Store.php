<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Repositories\CoinRepository;
use Blackshot\CoinMarketSdk\Requests\CoinRequest;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class Store extends Controller
{
    /**
     * @param CoinRequest $request
     * @return RedirectResponse
     */
    public function index(CoinRequest $request): RedirectResponse
    {
        try {
            CoinRepository::store($request);
            flash('Токен сохранен')->success();

            return redirect()->route('coins.home');
        } catch (Exception $exception) {
            flash('Не удалось сохранить данные.')->error();
            Log::error($exception->getMessage());
        }

        return back()->withInput();
    }
}
