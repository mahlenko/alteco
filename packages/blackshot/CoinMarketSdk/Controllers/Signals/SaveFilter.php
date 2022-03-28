<?php

namespace Blackshot\CoinMarketSdk\Controllers\Signals;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Repositories\UserSettingsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SaveFilter extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $filter = $request->get('filter');

        if (!key_exists('signals', $filter)) $filter['signals'] = [];
        if (!$filter['days']) $filter['days'] = 7;
        if (!$filter['min_rank']) $filter['min_rank'] = 30;
        if (!key_exists('categories_uuid', $filter)) $filter['categories_uuid'] = [];

        $filter['min_rank'] = abs($filter['min_rank']);
        $filter['days'] = abs($filter['days']);

        if ($request->has('filter')) {
            UserSettingsRepository::saveJson('signal_filter', $filter);
        } else {
            UserSettingsRepository::empty('signal_filter');
        }

        return redirect()->route('signals.home');
    }
}
