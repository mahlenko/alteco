@extends('blackshot::layouts.app')

@section('content')
    @php($current = $coin->current())

    <section class="item" id="item">
        <div class="container">
            {{-- Info --}}
            <div class="item__box d-flex">
                {{-- token --}}
                <div class="item-info">
                    <div class="item-info__top d-flex">
                        @if ($coin->info)
                            <img src="{{ $coin->info->logo }}" class="item-info__pic" alt="" height="32">
                        @endif
                        <h1 class="item-info__name">
                            {{ $coin->name }}
                        </h1>
                        <div class="item-info__el">
                            {{ $coin->symbol }}
                        </div>
                    </div>

                    <div class="item-info__flex d-flex">
                        <div class="item-info__block active">
                            Rank #{{ $coin->rank }}
                        </div>
                        @if ($coin->info)
                        <div class="item-info__block">
                            {{ \Illuminate\Support\Str::ucfirst($coin->info->category) }}
                        </div>
                        @endif
                    </div>

                    <a href="https://coinmarketcap.com/currencies/{{ $coin->slug }}/" class="item-info__site" target="blank">
                        https://coinmarketcap.com/
                    </a>

                    @isset($coin->info->urls)
                    <div class="item-info__links d-flex">
                        @foreach($coin->info->urls->groupBy('type') as $group => $links)
                            @if ($links->count() > 1)
                                <div class="dropdown m-1">
                                    <button class="dropdown item-info__one" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ \Illuminate\Support\Str::headline($group) }}
                                        <img src="{{ asset('images/arr-black.svg') }}" alt="" class="svg">
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-macos dropdown-menu-dark" aria-labelledby="dropdownMenuButton1">
                                        @foreach($links as $link)
                                            <li>
                                                <a class="dropdown-item" href="{{ $link->url }}" target="_blank">
                                                    {{ parse_url($link->url, PHP_URL_HOST) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <a href="{{ $links->first()->url }}" class="item-info__one">
                                    {{ \Illuminate\Support\Str::headline($group) }}
                                    <img src="{{ asset('images/add.svg') }}" alt="" class="svg">
                                </a>
                            @endif
                        @endforeach
                    </div>
                    @endif
                    <p class="item-info__text">
                        ???? ?????????????????????? ?? {{ $coin->created_at->format('d.m.Y') }}
                    </p>
                </div>

                {{-- extend info --}}
                <div class="item-content">
                    <div class="item-content__top d-flex">
                        <div class="item-content__el">
                            <p class="item-content__label">
                                {{ $coin->name }} Price ({{ $coin->symbol }})
                            </p>
                            <div class="item-content__flex d-flex">
                                <p class="item-content__main">
{{--                                    @include('blackshot::coins.partials.price', ['price' => $coin->price])--}}
                                    $
                                    <span @if ($coin->price > 0.0001)data-counter-step="10"@endif data-number="{{ $coin->price }}" data-decimals="{{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::decimals($coin->price) }}">
                                        {{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($coin->price) }}
                                    </span>
                                </p>

                                @include('blackshot::coins.partials.badge-position', ['percent' => $coin->percent_change_1h])
                            </div>
                        </div>
                        <div class="item-content__el">
                            <p class="item-content__label">
                                ?????????????? AltEco
                            </p>

                            <div class="step-progress" data-value="{{ $coin->alteco ?? 0 }}">
                                <div class="step-progress__container">
                                    <span class="step" data-max="9"></span>
                                    <span class="step" data-max="19"></span>
                                    <span class="step" data-max="29"></span>
                                    <span class="step" data-max="39"></span>
                                    <span class="step" data-max="49"></span>
                                    <span class="step" data-max="59"></span>
                                    <span class="step" data-max="69"></span>
                                    <span class="step" data-max="79"></span>
                                    <span class="step" data-max="89"></span>
                                    <span class="step" data-max="100"></span>
                                </div>
                                <span class="label">0</span>
                            </div>
                        </div>
                        <div class="item-content__el">
                            <p class="item-content__label">
                                ????????. Alpha
                            </p>
                            <p class="item-content__info @if(!is_null($coin->alpha)){{ $coin->alpha >= 0 ? 'item-content__info_green' : 'item-content__info_red' }}@endif">
                                @if(!is_null($coin->alpha))
                                    {{ $coin->alpha }}%
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="item-content__el">
                            <p class="item-content__label">
                                ????????. Kalmar
                            </p>
                            <p class="item-content__info @if(!is_null($coin->squid)){{ $coin->squid >= 0 ? 'item-content__info_green' : 'item-content__info_red' }}@endif">
                                @if(!is_null($coin->squid))
                                    {{ $coin->squid }}%
                                @else
                                    -
                                @endif
                            </p>
                        </div>

                        <div class="table__icons d-flex">
                            @php($favorite = \Illuminate\Support\Facades\Auth::user()->favoritesUuids->pluck('coin_uuid')->contains($coin->uuid))
                            <a href="javascript:void(0);" class="table__star {{ $favorite ? 'able' : null }}" title="Add to favorites" onclick="return favorites(this, '{{ $coin->uuid }}')">
                                <img src="{{ asset('images/star-big.svg') }}" alt="" class="svg">
                            </a>

                            @auth()
                                @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                                    <a href="{{ route('coins.edit', $coin) }}" style="color: black" title="??????????????????????????">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.5rem; height: 1.5rem" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                @endif
                            @endif

{{--                            @php($tracking = \Illuminate\Support\Facades\Auth::user()->trackings->where('uuid', $coin->uuid)->count())--}}
{{--                            <a href="javascript:void(0);" class="table__icon {{ $tracking ? 'able' : null }}" title="tracking" onclick="return tracking(this, '{{ $coin->uuid }}')">--}}
{{--                                <img src="{{ asset('images/fig-big.svg') }}" alt="" class="svg">--}}
{{--                            </a>--}}
                        </div>
                    </div>

                    <div class="item-content__bottom d-flex">
                        <div class="item-content__one">
                            <p class="item-content__label">
                                Market Cap
                            </p>
                            <p class="item-content__sum">
                                @if ($current->market_cap)
                                ${{ number_format($current->market_cap) }}
                                @endif
                            </p>
{{--                            <p class="item-content__num item-content__num_red">0.48</p>--}}
                        </div>
                        <div class="item-content__one">
                            <p class="item-content__label">
                                Fully Diluted Market Cap
                            </p>
                            <p class="item-content__sum">
                                @if($current->fully_diluted_market_cap)
                                ${{ number_format($current->fully_diluted_market_cap) }}
                                @endif
                            </p>
{{--                            <p class="item-content__num item-content__num_green">0.11</p>--}}
                        </div>
                        <div class="item-content__one">
                            <div class="item-content__label">
                                Volume
                                <div class="item-content__time">24h</div>
                            </div>
                            <p class="item-content__sum">
                                @if ($current->volume_24h_reported || $current->volume_24h)
                                ${{ number_format($current->volume_24h_reported ?? $current->volume_24h) }}
                                @endif
                            </p>
{{--                            <p class="item-content__num item-content__num_red">11,28</p>--}}

                            <p class="item-content__label item-content__label_last">
                                Volume / Market Cap
                            </p>
                            <p class="item-content__sum">
                                @include('blackshot::coins.partials.price', ['price' => ($current->volume_24h_reported ?? $current->volume_24h)])
                                / @include('blackshot::coins.partials.price', ['price' => $current->market_cap])
                            </p>
                        </div>
                        <div class="item-content__one">
                            <p class="item-content__label">
                                Circulating Supply
                            </p>
                            <div class="item-content__line d-flex">
                                <p class="item-content__sum">
                                    @if($current->circulating_supply)
                                    ${{ number_format($current->circulating_supply) }}
                                    @endif
                                </p>
                                <p class="item-content__proc">
                                    @if ($current->circulating_supply && $current->total_supply)
                                        @php($percent_supply = intval($current->circulating_supply / $current->total_supply  * 100))
                                        {{ $percent_supply }}%
                                    @endif
                                </p>
                            </div>

                            <div class="item-content__load">
                                <div class="item-content__progress" style="width: {{ $percent_supply ?? 0 }}%"></div>
                            </div>

                            @if($current->max_supply)
                            <div class="item-content__row d-flex">
                                <p class="item-content__label">
                                    Max Supply
                                </p>
                                <p class="item-content__sum">
                                    {{ number_format($current->max_supply) }}
                                </p>
                            </div>
                            @endif
                            <div class="item-content__row d-flex">
                                <p class="item-content__label">
                                    Total Supply
                                </p>
                                <p class="item-content__sum">
                                    {{ number_format($current->total_supply) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- founds --}}
            @php($founds = $coin->categories->where('type', \Blackshot\CoinMarketSdk\Models\CategoryModel::TYPE_FOUNDS))
            @if ($founds->count())
            <div class="item-wrap">
                <p class="item-wrap__title">
                    ???????????????????????????? ??????????:
                </p>

                <div class="item-wrap__links d-flex">
                    @foreach($founds as $category)
                        <span class="portfolio-item">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Rating graph --}}
            <div class="item-graph">
                <h2 class="item-graph__title">{{ $coin->name }} ({{ $coin->symbol }})</h2>
            </div>

            <ul class="tabs">
                <li class="tab show" data-for="coin_chart_rank">??????????????</li>
                <li class="tab" data-for="coin_chart_prices">????????</li>
            </ul>

            <style>.charts-container{width: 100%; height: 500px; position: relative; margin-bottom: 3rem}</style>
            <div id="coin_chart_rank" class="charts-container" data-reverse="true" data-graph-json='@json($charts['rank'])'>
                @if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())
                <div class="blur-container">
                    <a href="{{ route('subscribe') }}" class="btn btn1">
                        ???????????????? ????????????????
                    </a>
                </div>
                @endif
            </div>

            <div id="coin_chart_prices" class="charts-container" data-prefix="$" data-graph-json='@json($charts['prices'])'>
                @if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())
                    <div class="blur-container">
                        <a href="{{ route('subscribe') }}" class="btn btn1">
                            ???????????????? ????????????????
                        </a>
                    </div>
                @endif
            </div>

            <div class="@if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())blur-text @endif">
                {!! $coin->info->description ?? '' !!}
            </div>

            <div style="margin-top: 2rem;" class="@if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())blur-text @endif">
                {!! $coin->alteco_desc ?? '' !!}
            </div>
        </div>
    </section>

    <script>
        /**
         * ?????????????? ???????????? ?? ??????????????????
         * @param element
         * @param uuid
         */
        function favorites(element, uuid)
        {
            // let star = element.querySelector('.fa-star')

            axios.post('{{ route('users.favorite') }}', {uuid})
                .then(response => {
                    if (response.data.data.favorite) {
                        element.classList.add('able')
                    } else {
                        element.classList.remove('able')
                    }
                })

            // return tracking(trecking_link, uuid)
        }

        /**
         * ?????????????? ???????????? ?? ??????????????????????????
         * @param element
         * @param uuid
         */
        {{--function tracking(element, uuid)--}}
        {{--{--}}
        {{--    axios.post('{{ route('users.tracking') }}', { uuid })--}}
        {{--        .then(response => {--}}
        {{--            if (response.data.data.tracking === 'delete') {--}}
        {{--                element.classList.remove('able')--}}
        {{--            } else {--}}
        {{--                element.classList.add('able')--}}
        {{--            }--}}
        {{--        })--}}
        {{--}--}}
    </script>
@endsection
