@extends('blackshot::layouts.app')

@section('content')

    {{--  --}}
    <div class="d-flex align-items-center justify-content-between px-2">
        <div>
            <h1>
                <i class="fab fa-bitcoin text-secondary" aria-hidden="true"></i>
                <strong>Coins</strong>
            </h1>
            <p class="text-secondary">
                Total of {{ $coins->total() }} {{ \Illuminate\Support\Str::plural('coin', $coins->total()) }}
                on {{ $coins->lastPage() }} {{ \Illuminate\Support\Str::plural('page', $coins->lastPage()) }}
            </p>
        </div>

        <a href="{{ route('signals.home') }}" class="btn btn-outline-success text-nowrap">
            <i class="fas fa-chess-queen"></i>
            View my signals
        </a>
    </div>

{{--    @dd(\Illuminate\Support\Facades\Auth::user()->trackings)--}}

    {{-- filter --}}
    <form action="{{ route('coins.filter.store') }}" method="post" class="mb-4 px-2">
        @csrf

        <div class="rounded p-3 border bg-light mb-3">
            <div class="d-flex flex-column flex-md-row align-items-start mb-2">
                <div class="col-12 col-md-4 mb-1 mx-1">
                    <label for="search" class="mb-2">
                        <strong>Search</strong>:
                        <small class="text-secondary">Name or symbol coin</small>
                    </label>

                    <input
                        type="search"
                        class="form-control"
                        placeholder="Start the search..."
                        name="filter[q]"
                        value="{{ $filter->q ?? null }}"
                        id="search">
                </div>

                <div class="col-12 col-md-4 mb-1 mx-1">
                    <label for="category" class="mb-2">
                        <strong>Categories</strong>:
                    </label>

                    <select name="filter[category_uuid][]" multiple id="category">
                        @foreach($categories as $value => $label)
                            <option
                                @if(isset($filter->category_uuid) && in_array($value, $filter->category_uuid)) selected @endif
                                value="{{ $value }}"
                            >{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-md-3 mx-1">
                    <label for="change-picker" class="mb-2 me-2 text-nowrap">
                        <strong>Change of rank for the period</strong>:
                    </label>
                    <div class="input-group">
                        <label for="change-picker" class="input-group-text">
                            <i class="far fa-calendar-alt"></i>
                        </label>
                        <input type="text"
                               name="filter[date]"
                               class="form-control"
                               id="change-picker"
                               value="{{ $change[0]->format('m/d/Y') }} - {{ $change[1]->format('m/d/Y') }}"
                               data-type="datepicker">
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mx-1">
                <i class="fas fa-filter"></i>
                Filter
            </button>
        </div>
    </form>

    {{--  --}}
    <table class="table table-hover">
        <thead class="table-secondary">
            <tr>
                <th width="50"></th>
                <th width="40">#</th>
                <th>
                    @include('blackshot::partials.sortable', ['column' => 'name'])
                    Name / Last percentage change
                </th>
                <th>
                    {{--                    @include('blackshot::partials.sortable', ['column' => 'price'])--}}
                    Price
                </th>
                <th>
                    @include('blackshot::partials.sortable', ['column' => 'rank'])
                    Rank
                </th>
                <th>
                    @include('blackshot::partials.sortable', ['column' => 'rank_period'])
                    Rank
                    <span class="ms-1 badge text-secondary bg-light">
                        {{ $change_diff->days + 1 }}d
                        selected in the filter
                    </span>
                </th>
                <th>
                    @include('blackshot::partials.sortable', ['column' => 'rank_30d'])
                    Rank
                    <span class="ms-1 badge text-secondary bg-light">30d</span></th>
                <th>
                    @include('blackshot::partials.sortable', ['column' => 'rank_60d'])
                    Rank
                    <span class="ms-1 badge text-secondary bg-light">60d</span></th>
                <th>Last updated</th>
            </tr>
        </thead>
        <tbody>
        @foreach($coins as $coin)
{{--            @php($current_quote = $coin->current())--}}
            <tr>
                <td>
                    <p class="d-flex mb-0">
                        <a href="javascript:void(0);" class="favorite p-2" title="Add to favorites" onclick="return favorites(this, '{{ $coin->uuid }}')">
                            @if ($favorites->where('uuid', $coin->uuid)->count())
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star"></i>
                            @endif
                        </a>

                        <a href="javascript:void(0);" class="p-2" title="tracking" onclick="return tracking(this, '{{ $coin->uuid }}')">
                            @if ($tracking->where('uuid', $coin->uuid)->count())
                                <i class="fas fa-chart-line"></i>
                            @else
                                <i class="fas fa-chart-line text-secondary"></i>
                            @endif
                        </a>
                    </p>
                </td>
                <td class="d-none d-md-table-cell">{{ ($coins->currentPage() * $coins->perPage()) - $coins->perPage() + $loop->iteration }}.</td>
                <td>
                    <div class="d-flex flex-nowrap align-items-center">
                        @if(!empty($coin->info->logo))
                            <img src="{{ $coin->info->logo }}" class="me-1" alt="" height="32">
                        @endif

                        <a href="{{ route('coins.view', $coin->uuid) }}"
                           class="ms-1 me-2 text-dark text-nowrap"><strong>{{ $coin->name }}</strong></a>

                        <span class="text-secondary">{{ $coin->symbol }}</span>

                        @include('blackshot::coins.partials.badge-position-text', ['percent' => $coin->percent_change_1h])
                    </div>
                </td>

                <td>
                    <strong>
                        @include('blackshot::coins.partials.price', ['price' => $coin->price])
                    </strong>
                </td>

                <td>
                    {{ $coin->rank }}
                </td>

                <td>
{{--                    @php($changeRank = $coin->changeRankByPeriod(new DateTimeImmutable($change[0]), new DateTimeImmutable($change[1])))--}}
{{--                    @include('blackshot::partials.badge-position-text', ['position' => $changeRank * -1])--}}
                    @include('blackshot::partials.badge-position-text', ['position' => $coin->rank_period])
                </td>

                <td>
{{--                    @php($changeRank = $coin->changeRankByPeriod(new DateTimeImmutable('now - 30 days'), new DateTimeImmutable('now')))--}}
{{--                    @include('blackshot::partials.badge-position-text', ['position' => $changeRank * -1])--}}
                    @include('blackshot::partials.badge-position-text', ['position' => $coin->rank_30d])
                </td>

                <td>
{{--                    @php($changeRank = $coin->changeRankByPeriod(new DateTimeImmutable('now -60 days'), new DateTimeImmutable('now')))--}}
{{--                    @include('blackshot::partials.badge-position-text', ['position' => $changeRank * -1])--}}
                    @include('blackshot::partials.badge-position-text', ['position' => $coin->rank_60d])
                </td>

                <td>
{{--                    @if($current_quote->last_updated)--}}
{{--                        {{ \Illuminate\Support\Carbon::createFromTimeString($current_quote->last_updated)->diffForHumans() }}--}}
{{--                    @endif--}}
                    {{ \Illuminate\Support\Carbon::createFromTimeString($coin->updated_at)->diffForHumans() }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $coins->appends(request()->input())->links() }}

    <script>
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
