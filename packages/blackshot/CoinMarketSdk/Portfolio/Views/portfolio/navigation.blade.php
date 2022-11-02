<nav class="portfolio__navigation">
    {{-- Portfolio total info --}}
    <ul class="portfolio__navigation-info">
        {{-- Текущий баланс --}}
        <li class="portfolio__navigation-item">
            <div class="item">
                <span class="icon current-balance">
                    <img src="{{ asset('css/img/case1.svg') }}" alt="" class="case-left__icon">
                </span>
                <div class="item__content">
                    <p class="title">Текущий баланс:</p>
                    <p class="item-content__main">
                        ${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($totalPrice) }}
                    </p>
                </div>
            </div>
        </li>

        {{-- Пасивный доход за год --}}
        <li class="portfolio__navigation-item">
            <div class="item">
                <span class="icon passive-year">
                    <img src="{{ asset('css/img/case2.svg') }}" alt="" class="case-left__icon">
                </span>
                <div class="item__content">
                    <p class="title">Пассивный доход в год:</p>
                    <p class="item-content__main">${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($profit['year']) }}</p>
                </div>
            </div>
        </li>

        {{-- Пасивный доход в месяц --}}
        <li class="portfolio__navigation-item">
            <div class="item">
                <span class="icon passive-month">
                    <img src="{{ asset('css/img/case3.svg') }}" alt="" class="case-left__icon">
                </span>
                <div class="item__content">
                    <p class="title">Пассивный доход в месяц:</p>
                    <p class="item-content__main">${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($profit['month']) }}</p>
                </div>
            </div>
        </li>
    </ul>

    {{-- Portfolio list --}}
    <ul class="portfolio-list">
        @foreach($portfolios as $item)
        <li class="portfolio__navigation-item {{ $item->id == $portfolio->id ? 'active' : null }}">
            <a href="{{ route('portfolio.home', $item->id) }}" class="item">
                <span class="icon">
                    <img src="{{ asset('css/img/case4.svg') }}" alt="">
                </span>
                <div class="item__content" style="white-space: nowrap">
                    <p class="title">{{ $item->name }}</p>
                    <p class="item-content__main">${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->items()->currentPrice()) }}</p>
                </div>
            </a>
        </li>
        @endforeach
    </ul>

    @if($portfolios->count() < 2)
    <a href="{{ route('portfolio.create') }}" data-modal data-title="Создать портфолио" class="link-add">
        <span class="icon-wrap">
            <svg class="icon"><use xlink:href="{{ asset('css/img/svg-sprite.svg#case5') }}"></use></svg>
        </span>
        <p class="case-left__text">Создать новый портфель</p>
    </a>
    @endif
</nav>
