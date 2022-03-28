@extends('blackshot::layouts.app')

@section('content')
    @php($current = $coin->current())

    <div class="d-flex flex-column flex-md-row">
        {{-- --}}
        <div class="col-md-4 mb-3">
            <div class="d-flex align-items-center mb-2">
                @if ($coin->info)
                <img src="{{ $coin->info->logo }}" alt="" height="32">
                @endif
                <h1 class="h2 mb-0 mx-3"><strong>{{ $coin->name }}</strong></h1>
                <strong class="badge bg-light text-secondary">{{ $coin->symbol }}</strong>
            </div>

            <span class="badge bg-secondary">
                Rank #{{ $coin->rank }}
            </span>

            <span class="badge bg-light text-secondary">
                @if ($coin->info)
                {{ \Illuminate\Support\Str::ucfirst($coin->info->category) }}
                @endif
            </span>

            <p class="mt-3">
                <a href="https://coinmarketcap.com/currencies/{{ $coin->slug }}/" class="btn btn-sm btn-warning" style="color: white; background: #fa7650; border-color: #fa7650" target="_blank">
                    <small class="me-1">
                        <i class="fas fa-external-link-alt"></i>
                    </small>
                    <small>https://coinmarketcap.com</small>
                </a>
            </p>

            @if ($coin->info && $coin->info->urls)
            <div class="d-flex flex-wrap align-items-start mt-3">
                @foreach($coin->info->urls->groupBy('type') as $group => $links)
                    @if ($links->count() > 1)
                    <div class="dropdown m-1">
                        <button class="btn btn-light btn-sm border dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <strong>
                                <small>{{ \Illuminate\Support\Str::headline($group) }}</small>
                            </strong>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-macos dropdown-menu-dark" aria-labelledby="dropdownMenuButton1">
                            @foreach($links as $link)
                            <li>
                                <a class="dropdown-item" href="{{ $link->url }}" target="_blank">
                                    <small>
                                        <strong>{{ parse_url($link->url, PHP_URL_HOST) }}</strong>
                                        <i class="fas fa-external-link-alt text-secondary ms-1"></i>
                                    </small>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                    <a href="{{ $links->first()->url }}"
                       class="btn btn-light btn-sm m-1 border"
                       target="_blank">
                        <strong>
                            <small>
                                {{ \Illuminate\Support\Str::headline($group) }}
                                <i class="fas fa-external-link-alt text-secondary ms-1"></i>
                            </small>
                        </strong>
                    </a>
                    @endif
                @endforeach
            </div>
            @endif

            <p class="mt-2 small">
                <span class="text-secondary">We have been tracking since</span>
                {{ $coin->created_at->format('d.m.Y') }}
            </p>
        </div>

        {{--  --}}
        <div class="col-md-8 ps-md-3">
            <div class="d-flex justify-content-between align-items-start">
                {{--  --}}
                <div>
                    <small class="text-secondary">
                        {{ $coin->name }} ({{ $coin->symbol }})
                    </small>

                    <p class="d-flex align-items-center">
                        <strong class="h3">
                            @include('blackshot::coins.partials.price', ['price' => $coin->price])
                        </strong>
                        @include('blackshot::coins.partials.badge-position', ['percent' => $coin->percent_change_1h])
                    </p>
                </div>

                {{--  --}}
                <ul class="list-inline mb-0 ms-3">
                    {{-- favorite --}}
                    <li class="list-inline-item">
                        <a href="javascript:void(0);" class="favorite text-decoration-none p-2" title="Add to favorites" onclick="return favorites(this, '{{ $coin->uuid }}')">
                            @if (\Illuminate\Support\Facades\Auth::user()->favorites->where('uuid', $coin->uuid)->count())
                                <i class="fas fa-star me-1"></i><small class="text-secondary">favorite</small>
                            @else
                                <i class="far fa-star me-1"></i><small class="text-secondary">favorite</small>
                            @endif
                        </a>
                    </li>

                    {{-- tracking --}}
                    <li class="list-inline-item">
                        <a href="javascript:void(0);" class="text-decoration-none p-2" title="tracking" onclick="return tracking(this, '{{ $coin->uuid }}')">
                            @if (\Illuminate\Support\Facades\Auth::user()->trackings->where('uuid', $coin->uuid)->count())
                                <i class="fas fa-chart-line me-1"></i><small class="text-secondary">tracking</small>
                            @else
                                <i class="fas fa-chart-line me-1 text-secondary"></i><small class="text-secondary">tracking</small>
                            @endif
                        </a>
                    </li>
                </ul>
            </div>

            <hr>

            <div class="d-flex flex-column flex-md-row">
                {{--  --}}
                <div class="col-md-3">
                    <p>
                        <small class="text-secondary">
                            Market Cap
                        </small>
                        <br>
                        <small>
                            <strong>
                                <small>${{ number_format($current->market_cap) }}</small>
                            </strong>
                        </small>
                    </p>
                </div>

                {{--  --}}
                <div class="col-md-3">
                    <p>
                        <small class="text-secondary">
                            Fully Diluted Market Cap
                        </small>
                        <br>
                        <small>
                            <strong>
                                <small>${{ number_format($current->fully_diluted_market_cap) }}</small>
                            </strong>
                        </small>
                    </p>
                </div>

                {{--  --}}
                <div class="col-md-3">
                    <p>
                        <small class="text-secondary">
                            Volume <span class="badge bg-light text-secondary">24h</span>
                        </small>
                        <br>
                        <small>
                            <strong>
                                <small>${{ number_format($current->volume_24h_reported ?? $current->volume_24h) }}</small>
                            </strong>
                        </small>
                    </p>

                    <p>
                        <small class="text-secondary">
                            Volume / Market Cap
                        </small>
                        <br>
                        <small>
                            <strong>
                                <small>
                                    @include('blackshot::coins.partials.price', ['price' => ($current->volume_24h_reported ?? $current->volume_24h)])
                                    / @include('blackshot::coins.partials.price', ['price' => $current->market_cap])
                                </small>
                            </strong>
                        </small>
                    </p>
                </div>

                {{--  --}}
                <div class="col-md-3">
                    <p>
                        <small class="text-secondary">
                            Circulating Supply
                        </small>
                        <br>
                        <small>
                            <strong>
                                <small>${{ number_format($current->circulating_supply) }}</small>
                            </strong>
                        </small>
                    </p>

                    <p class="d-flex justify-content-between mb-1">
                        <small class="text-secondary">
                            Max Supply
                        </small>
                        <small>
                            <strong>{{ number_format($current->max_supply) }}</strong>
                        </small>
                    </p>

                    <p class="d-flex justify-content-between">
                        <small class="text-secondary">
                            Total Supply
                        </small>
                        <small>
                            <strong>{{ number_format($current->total_supply) }}</strong>
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex flex-column flex-md-row">
        @if ($coin->info && $coin->info->tags)
            <div class="col-md-4 pe-md-3">
                <p class="mt-3 mb-2">
                    <small class="text-secondary">Tags ({{ $coin->info->tags->count() }}):</small>
                </p>

                <p id="tags">
                    @foreach($coin->info->tags as $tag)
                        <small class="badge text-secondary bg-light" @if($loop->index > 7)hidden @endif>
                            {{ $tag->name }}
                        </small>
                    @endforeach

                    @if($coin->info->tags->count() > 8)
                        <a href="javascript:void(0)"
                           class="badge text-primary text-decoration-none"
                           style="background-color: #e0ecff"
                           onclick="return toggle('#tags .badge', this)"
                        >
                            View all
                        </a>
                    @endif
                </p>
            </div>
        @endif

        @if ($coin->categories)
            <div class="col-md-4 ps-md-3">
                <p class="mt-3 mb-2">
                    <small class="text-secondary">Categories ({{ $coin->categories->count() }}):</small>
                </p>

                <p id="categories">
                    @foreach($coin->categories as $category)
                        <small class="badge text-secondary bg-light" @if($loop->index > 7)hidden @endif>
                            {{ $category->name }}
                        </small>
                    @endforeach

                    @if($coin->categories->count() > 8)
                        <a href="javascript:void(0)"
                           class="badge text-primary text-decoration-none"
                           style="background-color: #e0ecff"
                           onclick="return toggle('#categories .badge', this)"
                        >
                            View all
                        </a>
                    @endif
                </p>
            </div>
        @endif
    </div>

    <hr>

    <h2>
        <strong>
            {{ $coin->name }} ({{ $coin->symbol }})
        </strong>
    </h2>

    <p>{{ $coin->info->description ?? '' }}</p>

    {{-- Chart --}}
    <h2>Rank changes</h2>
    <style>#coin_chart{width: 100%;height: 500px;}</style>
    <div id="coin_chart" data-json='@json($charts)'></div>

    <script>
        function toggle(selector, removeElement)
        {
            let selectors = document.querySelectorAll(selector)
            if (selectors.length) {
                selectors.forEach(item => {
                    if (item.getAttribute('hidden') !== null) {
                        item.removeAttribute('hidden')
                    }
                })

                removeElement.remove()
            }
        }

        /**
         * Добавит монету в избранные
         * @param element
         * @param uuid
         */
        function favorites(element, uuid)
        {
            let star = element.querySelector('.fa-star')

            axios.post('{{ route('users.favorite') }}', {uuid})
                .then(response => {
                    if (response.data.data.favorite) {
                        star.classList.remove('far')
                        star.classList.add('fas')
                    } else {
                        star.classList.remove('fas')
                        star.classList.add('far')
                    }
                })

            let trecking_link = element
                .parentElement
                .parentElement
                .querySelector('.fa-chart-line')
                .parentElement

            return tracking(trecking_link, uuid)
        }

        /**
         * Добавит монету в отслеживаемые
         * @param element
         * @param uuid
         */
        function tracking(element, uuid)
        {
            let icon = element.querySelector('.fa-chart-line')

            axios.post('{{ route('users.tracking') }}', { uuid })
                .then(response => {
                    if (response.data.data.tracking === 'delete') {
                        icon.classList.add('text-secondary')
                    } else {
                        icon.classList.remove('text-secondary')
                    }
                })
        }
    </script>
@endsection
