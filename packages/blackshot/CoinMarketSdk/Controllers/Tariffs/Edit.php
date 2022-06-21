<?php

namespace Blackshot\CoinMarketSdk\Controllers\Tariffs;

use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Repositories\TariffRepository;
use Blackshot\CoinMarketSdk\Requests\TariffRequest;
use Illuminate\Http\RedirectResponse;

class Edit extends \App\Http\Controllers\Controller
{
    public function index(TariffModel $tariff = null)
    {
        return view('blackshot::tariff.edit', [
            'tariff' => $tariff,
            'breadcrumb_data' => $tariff,
            'tariffs' => TariffModel::where('id', '<>', $tariff?->id ?? null)->get()
        ]);
    }

    /**
     * @param TariffRequest $request
     * @return RedirectResponse
     */
    public function store(TariffRequest $request): RedirectResponse
    {
        $data = $request->validationData();

        if (key_exists('id', $data) && $data['id']) {
            $tariff = TariffModel::find($data['id']);
            $tariff = TariffRepository::update(
                $tariff,
                $data['name'],
                $data['amount'] ?? 0,
                $data['days'] ?? 1,
                $data['free'] ?? false,
                $data['default'] ?? false,
                $data['move']
            );
        } else {
            $tariff = TariffRepository::create(
                $data['name'],
                $data['amount'] ?? 0,
                $data['days'] ?? 1,
                $data['free'] ?? false,
                $data['default'] ?? false,
                $data['move']
            );
        }

        flash('Тариф успешно сохранен.')->success();

        return redirect()->route('tariffs.home');
    }
}
