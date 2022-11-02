<div class="case-table">
    <h2 class="case-table__title">
        Ваши активы
    </h2>

    @php($portfolio = $portfolio->calculateRatio()->sortByDesc('ratio'))

    @if ($portfolio->count())
    <table class="profile__table scan__table adaptive-table">
        <thead>
        <tr class="profile__row">
            <td class="active">Наименование</td>
            <td class="active">Цена</td>
            <td class="active">Активы</td>
{{--            <td class="active">Распределение</td>--}}
            <td class="active">Стейкинг</td>
            <td class="active">Ср. цена покупки</td>
            <td class="active">Прибыль / убыток</td>
            <td class="active"></td>
        </tr>
        </thead>
        <tbody>
        @foreach($portfolio as $item)
            <tr class="profile__row">
                <td class="active red" data-label="Наименование">
                    <div class="table__row d-flex">
                        <div class="table__flex table__flex_main d-flex">
                            @if(isset($item->coin->info) && $item->coin->info?->logo)
                                <img src="{{ $item->coin->info->logo }}" alt="" class="table__logo">
                            @endif
                            <p class="table__text">
                                <a href="{{ route('coins.view', $item->coin) }}">{{ $item->coin->name }}</a> {{ $item->coin->symbol }}
                            </p>
                        </div>
                    </div>
                </td>
                <td class="active nowrap-desktop" data-label="Цена">
                    $ {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->coin->price) }}
                </td>
                <td class="active" data-label="Активы">
                    $ {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->currentPrice()) }}
                    <span class="case-table__span">{{ $item->quantity() }} {{ $item->coin->symbol }}</span>
                </td>
{{--                <td class="active" data-label="Распределение">--}}
{{--                    <p class="table__num">--}}
{{--                        {{ $item->ratio }}%--}}
{{--                    </p>--}}
{{--                </td>--}}
                <td class="active" data-label="Стейкинг">
                    <p class="table__num global-flex gap-2">
                        <a href="{{ route('portfolio.stacking.create', [$item->portfolio_id, $item->coin]) }}" data-modal class="case-table__edit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="icon">
                                <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd" />
                            </svg>
                        </a>

                        <span>
                            @if($item->stacking->quantity())
                            {{ $item->stacking->quantity() }} {{ $item->coin->symbol }}
                            <span class="case-table__span" style="white-space: nowrap" title="Заработано монет на стейкинге">
                                + {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->stacking->profitQuantity()) }}
                            </span>
                            @endif
                        </span>
                    </p>
                </td>
                <td class="active" data-label="Ср. цена покупки">
                    <p class="table__num">
                        $ {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->priceBuyAverage()) }}
                    </p>
                </td>
                <td class="active {{ $item->profitPercent() > 0 ? 'green' : 'red' }}" data-label="Прибыль / убыток">
                    $ {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->profitPrice()) }}
                    <span class="table__num" style="padding: 0;">
                        {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->profitPercent()) }}%
                    </span>
                </td>
                <td class="active" data-label="Действия">

                    <div class="dropdown">
                        <a href="javascript:void()"></a>
                        <ul class="global-flex column gap-x-2">
                            <li>
                                <a href="{{ route('portfolio.transaction.home', ['portfolio' => $item->portfolio_id, 'coin' => $item->coin]) }}" data-modal>Транзакции</a>
{{--                                <a href="#" onclick="return confirm('Подтвердите удаление \'{{ $item->coin->name }}\' из портфеля.')">--}}
{{--                                    Удалить--}}
{{--                                </a>--}}
                            </li>
                            <li>
                                <a href="{{ route('portfolio.stacking.home', ['portfolio' => $item->portfolio_id, 'coin' => $item->coin]) }}" data-modal>Стейкинг</a>
                            </li>
                        </ul>
                    </div>

{{--                    <div class="table__row d-flex">--}}
{{--                        <a href="#" class="case-table__link">--}}
{{--                            <svg class="icon"><use xlink:href="css/img/svg-sprite.svg#table2"></use></svg>--}}
{{--                        </a>--}}
{{--                        <a href="#" class="case-table__link">--}}
{{--                            <svg class="icon"><use xlink:href="css/img/svg-sprite.svg#table3"></use></svg>--}}
{{--                        </a>--}}

{{--                        <a href="#" class="case-table__link">--}}
{{--                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 1.2rem" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />--}}
{{--                            </svg>--}}
{{--                        </a>--}}

{{--                        <a href="#" class="case-table__link">--}}
{{--                            <svg class="icon"><use xlink:href="css/img/svg-sprite.svg#table4"></use></svg>--}}
{{--                        </a>--}}
{{--                    </div>--}}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    @else
        <p>У вас пока нет активов.</p>
    @endif
</div>
