<?php

namespace Blackshot\CoinMarketSdk;

use App\Http\Controllers\Controller as ControllerDefault;

class Controller extends ControllerDefault
{
    public function ok(string $message = null, $data = null)
    {
        return response()->json([
            'ok' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function fail(string $message = null, $errors = null)
    {
        return response()->json([
            'ok' => false,
            'message' => $message,
            'errors' => $errors
        ]);
    }
}
