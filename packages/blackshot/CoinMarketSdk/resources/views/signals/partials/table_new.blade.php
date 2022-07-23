<table class="table profile__table scan__table adaptive-table">
    <thead class="table-secondary">
    <tr class="profile__row">
        <td class="active pad">Название</td>
        <td class="active {{ $sortable[$table_name]->column == 'rank' ? 'main' : null }}">
            Текущий ранг
            @include('blackshot::partials.sortable-signals', ['column' => 'rank', 'sortable' => $sortable[$table_name]])
        </td>
        <td class="nowrap-desktop active {{ $sortable[$table_name]->column == 'diff' ? 'main' : null }}">
            Изменение ранга за
            <span class="badge bg-light text-secondary ms-1">
                {{ $filter->days }} {{ trans_choice('день|дня|дней', $filter->days) }}
            </span>
            @include('blackshot::partials.sortable-signals', ['column' => 'diff', 'sortable' => $sortable[$table_name]])
        </td>
        <td class="active">Цена токена</td>

        <td class="active {{ $sortable[$table_name]->column == 'alteco' ? 'main' : null }}">
            AltEco Ранг
            @include('blackshot::partials.sortable-signals', ['column' => 'alteco', 'sortable' => $sortable[$table_name]])
        </td>
        <td class="active {{ $sortable[$table_name]->column == 'alpha' ? 'main' : null }}">
            Коэф. Alpha
            @include('blackshot::partials.sortable-signals', ['column' => 'alpha', 'sortable' => $sortable[$table_name]])
        </td>
        <td class="active {{ $sortable[$table_name]->column == 'squid' ? 'main' : null }}">
            Коэф. Kalmar
            @include('blackshot::partials.sortable-signals', ['column' => 'squid', 'sortable' => $sortable[$table_name]])
        </td>
        <td class="active"></td>
    </tr>
    </thead>
    <tbody class="signal-body">
    @foreach($coins as $coin)
        <tr class="profile__row">
            <td class="active pad" data-label="Название">
                <div class="table__row d-flex">
                    <div class="table__flex table__flex_main d-flex">
                        @if(!empty($coin->info->logo))
                            <img src="{{ $coin->info->logo }}" class="table__logo" alt="" height="32">
                        @endif
                        <p class="table__text">
                            <a href="{{ route('coins.view', $coin->uuid) }}">
                                <span>{{ $coin->name }}</span>
                            </a>
                            {{ $coin->symbol }}
                        </p>
                    </div>
                </div>
            </td>

            <td class="active">
                {{ $coin->rank }}
            </td>

            <td class="active {{ $coin->diff > 0 ? 'green' : 'red' }}">
                @include('blackshot::partials.badge-position-text', ['position' => $coin->diff])
            </td>

            <td class="active">
                @if($coin->first_historical_data)
                    @include('blackshot::coins.partials.price', ['price' => $coin->price])
                @endif
            </td>

            <td class="active">
                <div class="d-flex flex-center">
                    <div class="progress alteco" data-value="{{ $coin->alteco ? $coin->alteco - ($coin->alteco % 10) : 0 }}">
                        <div class="bar"></div>
                    </div>
                    <span>{{ $coin->alteco ?: '' }}</span>
                </div>
            </td>
            <td class="active">
                <div class="d-flex flex-center">
                    <div class="progress {{ $coin->alphaStatus }}">
                        <div class="bar" style="width: {{ $coin->alphaProgressPercent }}%"></div>
                    </div>

                    @if (!is_null($coin->alpha))
                        <span>{{ floatval($coin->alpha) }}%</span>
                    @endif
                </div>
            </td>
            <td class="active">
                <div class="d-flex flex-center">
                    <div class="progress {{ $coin->squidStatus }}">
                        <div class="bar" style="width: {{ $coin->squidProgressPercent }}%"></div>
                    </div>

                    @if (!is_null($coin->squid))
                        <span>{{ number_format($coin->squid, 2) }}</span>
                    @endif
                </div>
            </td>

            <td class="active">
                @if ($coins_buying_me->where('uuid', $coin->uuid)->count())
                    <a href="javascript:void(0);" class="table__sell btn" data-uuid="{{ $coin->uuid }}" data-buying>
                        Не покупаю
                    </a>
                @else
                    <a href="javascript:void(0);" class="table__buy btn" data-uuid="{{ $coin->uuid }}" data-buying>
                        Покупаю
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
