<?php

namespace Blackshot\CoinMarketSdk\Controllers\Coins;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Repositories\UserSettingsRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SaveFilter extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function index(Request $request): RedirectResponse
    {
        if ($request->has('filter')) {
            $filter = $request->get('filter');

            list($from, $to) = explode('-', $filter['date']);

            $from = (new DateTimeImmutable(trim($from)))->format('Y-m-d 00:00:00');
            $to = (new DateTimeImmutable(trim($to)))->format('Y-m-d 23:59:59');

            $filter['date'] = [$from, $to];
//            dd($filter);

            UserSettingsRepository::saveJson('coins_filter', $filter);
        } else {
            UserSettingsRepository::empty('coins_filter');
        }

        return redirect()->route('coins.home');
    }
}
