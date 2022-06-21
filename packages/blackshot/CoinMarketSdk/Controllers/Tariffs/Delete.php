<?php

namespace Blackshot\CoinMarketSdk\Controllers\Tariffs;

use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Requests\TariffDeleteRequest;
use Illuminate\Support\Facades\Auth;

class Delete extends \App\Http\Controllers\Controller
{
    public function index(TariffDeleteRequest $request)
    {
        $data = $request->validated();

        $tariff = TariffModel::find($data['id']);

        if (TariffModel::count() == 1) {
            flash('Удаление всех тарифов невозможно. Отредактируйте тариф или сначала создайте новый.')->error();
            return redirect()->route('tariffs.home');
        }

        if (Auth::check() && !Auth::user()->isAdmin()) {
            return abort(503);
        }

        $tariff->delete();

        flash('Тариф удален. Доступ закрыт для пользователей с данным тарифом.')->success();
        return redirect()->route('tariffs.home');
    }
}
