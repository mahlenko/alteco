<?php

namespace Blackshot\CoinMarketSdk\Controllers\Settings;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class Store extends Controller
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function index(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'api_key' => ['required', 'uuid'],
            'loading_coins_position' => ['required', 'numeric', 'min:1', 'max:5000']
        ]);

        foreach ($data as $key => $value) {
            Setting::updateValue($key, $value);
        }

        return redirect()->route('settings.home');
    }
}
