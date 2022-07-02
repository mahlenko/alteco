<?php

namespace Blackshot\CoinMarketSdk\Controllers\Banner;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\Banner;
use Blackshot\CoinMarketSdk\Models\TariffModel;
use Blackshot\CoinMarketSdk\Repositories\BannerRepository;
use Blackshot\CoinMarketSdk\Requests\BannerRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Edit extends Controller
{
    /**
     * @param Banner $banner
     * @return View
     */
    public function index(Banner $banner): View
    {
        return view('blackshot::banners.edit', [
            'banner' => $banner,
            'breadcrumb_data' => [
                'banner' => $banner
            ]
        ]);
    }

    /**
     * @param BannerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BannerRequest $request)
    {
        $data = $request->validated();

        try {
            BannerRepository::store($data, Auth::user());
            flash('Баннер успешно сохранен.')->success();
        } catch (\Exception $exception) {
            flash($exception->getMessage())->error();
            return back()->withInput();
        }

        return redirect()->route('banners.home');
    }
}
