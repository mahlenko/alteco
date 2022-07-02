<?php

namespace Blackshot\CoinMarketSdk\Controllers\Banner;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\Banner;
use Blackshot\CoinMarketSdk\Repositories\BannerRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class Delete extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'uuid' => ['required', Rule::exists('tariff_banners', 'uuid')]
        ]);

        try {
            $banner = Banner::find($data['uuid']);
            BannerRepository::delete($banner, Auth::user());

            flash('Баннер удален.')->success();
        } catch (Exception $exception) {
            flash($exception->getMessage())->error();
        }

        return back();
    }
}
