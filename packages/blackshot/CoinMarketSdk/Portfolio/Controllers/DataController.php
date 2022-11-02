<?php

namespace Blackshot\CoinMarketSdk\Portfolio\Controllers;

use Blackshot\CoinMarketSdk\Controller;
use Blackshot\CoinMarketSdk\Helpers\NumberHelper;
use Blackshot\CoinMarketSdk\Models\Coin;
use Blackshot\CoinMarketSdk\Portfolio\Requests\PriceCoinRequest;
use DateTimeImmutable;
use Illuminate\Http\JsonResponse;

class DataController extends Controller
{
    /**
     * @throws \Exception
     */
    public function getPriceCoin(PriceCoinRequest $request): JsonResponse
    {
        $data = $request->validated();

        $coin = Coin::find($data['coin_uuid']);

        if (!isset($data['date'])) {
            $price = $coin->current()?->price ?? 0;
        } else {
            $price = $coin->quotesByDate(new DateTimeImmutable($data['date']))->first()?->price ?? 0;
        }

        return $this->ok(data: ['price' => NumberHelper::format($price)]);
    }
}
