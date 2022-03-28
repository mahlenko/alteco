<?php

namespace Blackshot\CoinMarketSdk\Controllers\Settings;

use App\Http\Controllers\Controller;
use Blackshot\CoinMarketSdk\Models\Setting;

class Index extends Controller
{
    public function index()
    {
        return view('blackshot::settings.index', [
            'settings' => Setting::getAll()
        ]);
    }
}
