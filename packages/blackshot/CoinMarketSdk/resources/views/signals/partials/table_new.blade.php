<table class="table table-hover">
    <thead class="table-secondary">
    <tr>
        <th>Name</th>
        <th>
            @include('blackshot::partials.sortable-signals', ['column' => 'rank', 'sortable' => $sortable[$table_name]])
            Current Rank
        </th>
        <th>
            @include('blackshot::partials.sortable-signals', ['column' => 'diff', 'sortable' => $sortable[$table_name]])
            Diff Rank
            <span class="badge bg-light text-secondary ms-1">
                {{ $filter->days }} {{ \Illuminate\Support\Str::plural('day', $filter->days) }}
            </span>
        </th>
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
                        @if($coin->signal_max_diff)
                            <span class="badge bg-primary rounded-circle d-inline-block p-1 ms-1"></span>
                        @endif

                        @if($coin->signal_max_period)
                            <span class="badge bg-warning rounded-circle d-inline-block p-1 ms-1"></span>
                        @endif

                        @if($coin->signal_more_change_rank)
                            <span class="badge bg-success rounded-circle d-inline-block p-1 ms-1"></span>
                        @endif
                    </span>
                </div>
            </td>

            <td>
                {{ $coin->rank }}
            </td>

            <td>
                @include('blackshot::partials.badge-position-text', ['position' => $coin->diff])
            </td>

            <td>
                @if($coin->first_historical_data)
                    {{ \Illuminate\Support\Carbon::createFromTimeString($coin->updated_at)->diffForHumans() }}
                @endif
            </td>

            <td>
                @if ($coins_buying_me->where('uuid', $coin->uuid)->count())
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

@if ($coins instanceof \Illuminate\Pagination\LengthAwarePaginator)
{!! $coins->links() !!}
@endif
