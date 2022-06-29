<?php

namespace Blackshot\CoinMarketSdk\Controllers\Tariffs\Banner;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\TariffBanner;
use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Repositories\BannerRepository;
use Blackshot\CoinMarketSdk\Requests\TariffBannerRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Edit extends Controller
{
    /**
     * @param TariffModel $tariff
     * @param TariffBanner $banner
     * @return View
     */
    public function index(TariffModel $tariff, TariffBanner $banner): View
    {
        return view('blackshot::tariff.banner.edit', [
            'tariff' => $tariff,
            'banner' => $banner,
            'breadcrumb_data' => [
                'tariff' => $tariff,
                'banner' => $banner
            ]
        ]);
    }

    /**
     * @param TariffBannerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TariffBannerRequest $request)
    {
        $data = $request->validated();

        try {
            BannerRepository::store($data, Auth::user());
            flash('Баннер успешно сохранен.')->success();
        } catch (\Exception $exception) {
            flash($exception->getMessage())->error();
            return back()->withInput();
        }

        return redirect()->route('tariffs.edit', [
            'tariff' => $data['tariff_id']
        ]);
    }
}
