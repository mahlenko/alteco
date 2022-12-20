<div class="global-flex between y-center mobile-column" style="margin-bottom: 1.5rem;">
    <div class="item-content__el">
        <p class="item-content__label">Текущий баланс</p>
        <div class="item-content__flex d-flex">
            <p class="item-content__main">
                ${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($portfolio->items()->currentPrice()) }}
            </p>

            @php($profit = $portfolio->items()->profitPercent())
            <div class="item-content__block item-content__block_{{ $profit > 0 ? 'green' : 'red' }}" title="%, изменения портфеля за все время">
{{--                <p>{{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format(abs($profit)) }}</p>--}}
                <p>-</p>
                <img src="{{ asset('css/img/arr.svg') }}" alt="">
            </div>
        </div>
        <div class="case-right__flex d-flex">
            <p class="case-right__sum {{ $changePrice24 > 0 ? 'green' : 'red' }}">
                $ {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($changePrice24) }}
            </p>
            <div class="case-right__time">
                24h
            </div>
        </div>
    </div>
    <a href="{{ route('portfolio.transaction.create', $portfolio) }}"
       id="addTransaction"
       data-modal
       data-title="Добавить транзакцию"
       class="global-flex y-center link-add-asset">
        <svg class="icon"><use xlink:href="{{ asset('css/img/svg-sprite.svg#case6') }}"></use></svg>
        <span>Добавить актив</span>
    </a>
</div>

<div class="case-right__stat">
    <div class="case-right__row d-flex">
        <div class="case-right__tabs">
            <a href="#" class="case-right__link active">
                График
            </a>
            <a href="#" onclick="return alert('В разработке...')" class="case-right__link">
                Распределение
            </a>
        </div>
        <div class="case-right__tabs">
            @foreach(\Blackshot\CoinMarketSdk\Portfolio\Enums\CurrencyEnum::cases() as $case)
                <a href="#"
                   onclick="event.preventDefault(); return updateChart(document.querySelector('#chart'), this)"
                   data-currency="{{ $case->name }}"
                   class="case-right__link {{ $loop->first ? 'active' : null }}">{{ $case->value }}</a>
            @endforeach
        </div>
        <div class="case-right__tabs">
            @foreach(\Blackshot\CoinMarketSdk\Portfolio\Enums\PeriodEnum::cases() as $case)
                <a href="#" onclick="event.preventDefault(); return updateChart(document.querySelector('#chart'), this)"
                   data-period="{{ $case->name }}"
                   class="case-right__link {{ $loop->first ? 'active' : null }}">
                    {{ $case->value }}
                </a>
            @endforeach
        </div>
    </div>
</div>


{{--    <div id="chart-pie"></div>--}}
<div id="chart"
     data-chart="{{ route('api.portfolio.charts', [
    'portfolio_id' => $portfolio->getKey(),
    'period' => \Blackshot\CoinMarketSdk\Portfolio\Enums\PeriodEnum::hours24->name,
    'currency' => \Blackshot\CoinMarketSdk\Portfolio\Enums\CurrencyEnum::USD->name,
    ]) }}"></div>

<script>
    function updateChart(container, el) {
        el.parentNode.querySelector('.active').classList.remove('active')
        el.classList.add('active')

        let query = '?portfolio_id={{ $portfolio->getKey() }}';

        query += '&period=' + document.querySelector('.active[data-period]').dataset.period;
        query += '&currency=' + document.querySelector('.active[data-currency]').dataset.currency;

        let url = '{{ route('api.portfolio.charts') }}' + query
        charts.loadData(container, url)
    }
</script>
