<table class="table table-hover">
    <thead class="table-secondary">
    <tr>
        <th>Name</th>
        <th>
            @include('blackshot::partials.sortable-signals', ['column' => 'rank', 'sortable' => $sortable[$table_name]])
            Current Rank
        </th>
{{--        <th>Today change</th>--}}
{{--        <th>--}}
{{--            Max changing--}}
{{--            <span class="badge bg-light text-secondary ms-1">--}}
{{--                {{ $filter->days }} {{ \Illuminate\Support\Str::plural('day', $filter->days) }}--}}
{{--            </span>--}}
{{--        </th>--}}
        <th>
            @include('blackshot::partials.sortable-signals', ['column' => 'rank_diff', 'sortable' => $sortable[$table_name]])
            Rank
            <span class="badge bg-light text-secondary ms-1">
                {{ $filter->days }} {{ \Illuminate\Support\Str::plural('day', $filter->days) }}
            </span>
        </th>
        {{--                <th>SMA</th>--}}
        {{--                <th>Open</th>--}}
        {{--                <th>Low</th>--}}
        {{--                <th>High</th>--}}
        {{--                <th>Close</th>--}}
        <th>Last updated</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @foreach($coins as $coin)
        <tr>
            <td>
                <div class="d-flex flex-nowrap align-items-center">
                    @if(!empty($coin->info->logo))
                        <img src="{{ $coin->info->logo }}" class="me-1" alt="" height="32">
                    @endif

                    <a href="{{ route('coins.view', $coin->uuid) }}"
                       class="ms-1 me-2 text-dark text-nowrap"><strong>{{ $coin->name }}</strong></a>

                    <span class="text-secondary">{{ $coin->symbol }}</span>

                    <span class="ms-2">
                            @if($coin->max_up_today && array_search('max_up_today', $filter->signals) !== false)
                            <span class="badge bg-primary rounded-circle d-inline-block p-1 ms-1"></span>
                        @endif

                        @if($coin->max_up_period && array_search('max_up_period', $filter->signals) !== false)
                            <span class="badge bg-warning rounded-circle d-inline-block p-1 ms-1"></span>
                        @endif

                        @if($coin->more_30_rank && array_search('more_30_rank', $filter->signals) !== false)
                            <span class="badge bg-success rounded-circle d-inline-block p-1 ms-1"></span>
                        @endif
                        </span>
                </div>
            </td>

            <td>
                {{ $coin->rank }}
            </td>

{{--            <td>--}}
{{--                {{ $coin->max_up_today_rank ?? '---' }}--}}
{{--            </td>--}}

{{--            <td>--}}
                {{--                    @include('blackshot::partials.badge-position-text', ['position' => $coin->changing_rank_max])--}}
{{--                {{ $coin->max_up_period_rank ?? '---' }}--}}
{{--            </td>--}}

            <td>
                @include('blackshot::partials.badge-position-text', ['position' => $coin->rank_diff])
            </td>

            {{--                <td>--}}
            {{--                    ${{ number_format($coin->sma, 2) }}--}}
            {{--                </td>--}}

            {{--                <td>${{ number_format($coin->open(new DateTimeImmutable('now')), 2) }}</td>--}}
            {{--                <td>${{ number_format($coin->low(new DateTimeImmutable('now')), 2) }}</td>--}}
            {{--                <td>${{ number_format($coin->high(new DateTimeImmutable('now')), 2) }}</td>--}}
            {{--                <td>${{ number_format($coin->close(new DateTimeImmutable('now')), 2) }}</td>--}}

            <td>
                @if($coin->first_historical_data)
                    {{ \Illuminate\Support\Carbon::createFromTimeString($coin->current()->last_updated)->diffForHumans() }}
                @endif
            </td>

            <td>
                @if (\Illuminate\Support\Facades\Auth::user()->buyingCoins->where('uuid', $coin->uuid)->count())
                    <a href="#" class="btn btn-sm btn-outline-danger" data-uuid="{{ $coin->uuid }}" data-buying>
                        Cancel
                    </a>
                @else
                    <a href="#" class="btn btn-sm btn-outline-success" data-uuid="{{ $coin->uuid }}" data-buying>
                        Purchasing
                    </a>
                @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
