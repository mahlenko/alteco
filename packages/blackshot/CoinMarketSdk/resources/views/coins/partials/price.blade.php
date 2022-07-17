@if(!is_null($price))
    ${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($price) }}
@endif
