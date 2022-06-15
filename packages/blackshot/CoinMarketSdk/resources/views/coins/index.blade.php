@extends('blackshot::layouts.app')

@section('content')
    <div class="scan__flex d-flex">
        <div class="scan__left">
            <h1 class="pages__title">
                Монеты
            </h1>
            <p class="scan__search">
                Всего {{ $coins->total() }} монет
                на {{ $coins->lastPage() }} страницах.
            </p>
        </div>
        <a href="{{ route('signals.home') }}" class="scan__show btn btn1">
            Показать мои сигналы
        </a>
    </div>

{{--    @dd(\Illuminate\Support\Facades\Auth::user()->trackings)--}}

    {{-- filter --}}
    <div class="scan__box">
        <form action="{{ route('coins.filter.store') }}" method="post" class="scan-form d-flex">
            @csrf

            {{-- Search --}}
            <div class="scan-form__item scan-search">
                <label for="search_q">Поиск</label><br>
                <input type="text"
                       name="filter[q]"
                       id="search_q"
                       value="{{ $filter->q ?? null }}"
                       placeholder="Название монеты">

                <a href="#" class="scan-form__link">
                    <img src="{{ asset('css/img/search.svg') }}" alt="">
                </a>
            </div>

            {{-- Category --}}
            <div class="scan-form__item">
                <label for="category">Категория</label><br>
                <select class="select" name="filter[category_uuid][]" multiple id="category">
                    @foreach($categories as $value => $label)
                        @if ($label instanceof \Illuminate\Support\Collection)
                            <optgroup label="{{ __('categories.'.$value) }}">
                                @foreach($label as $val => $text)
                                    <option
                                        @if(isset($filter->category_uuid) && in_array($val, $filter->category_uuid)) selected @endif
                                    value="{{ $val }}"
                                    >{{ $text }}</option>
                                @endforeach
                            </optgroup>
                        @else
                            <option
                                @if(isset($filter->category_uuid) && in_array($value, $filter->category_uuid)) selected @endif
                            value="{{ $value }}"
                            >{{ $label }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="scan-form__item">
                <label for="change-picker">Изменение ранга за период:</label><br>
                <input type="text"
                       name="filter[date]"
                       class="date"
                       id="change-picker"
                       value="{{ $change[0]->format('m/d/Y') }} - {{ $change[1]->format('m/d/Y') }}"
                       data-type="datepicker">
            </div>

            <div>
                <br>
                <button type="submit" class="scan-form__btn btn btn2">
                    Применить
                </button>
            </div>
        </form>
    </div>

    {{--  --}}
    <div class="scan__wrap">
        @php($current_params = \Illuminate\Support\Facades\Request::input())

        <table class="table profile__table scan__table adaptive-table">
            <thead class="table-secondary">
                <tr class="profile__row">
{{--                    <td class="active" data-label="">#</td>--}}
                    <td data-label="Name" class="active {{ $sortable['column'] == 'name' ? 'main' : null }}">
                        Имя / Последнее изменение, %
                        @include('blackshot::partials.sortable', ['column' => 'name'])
                    </td>
                    <td class="active">Цена</td>
                    <td class="active {{ $sortable['column'] == 'rank' ? 'main' : null }}">
                        Ранг
                        @include('blackshot::partials.sortable', ['column' => 'rank'])
                    </td>
                    <td class="active {{ $sortable['column'] == 'rank_period' ? 'main' : null }}">
                        Ранг
                        <span class="ms-1 badge text-secondary bg-light">
                            {{ $change_diff->days + 1 }}д
                        </span>
                        @include('blackshot::partials.sortable', ['column' => 'rank_period'])
                    </td>
                    <td class="active {{ $sortable['column'] == 'rank_30d' ? 'main' : null }}">
                        Ранг
                        <span class="ms-1 badge text-secondary bg-light">30д</span>
                        @include('blackshot::partials.sortable', ['column' => 'rank_30d'])
                    </td>
                    <td class="active {{ $sortable['column'] == 'rank_60d' ? 'main' : null }}">
                        Ранг
                        <span class="ms-1 badge text-secondary bg-light">60д</span>
                        @include('blackshot::partials.sortable', ['column' => 'rank_60d'])
                    </td>
                    <td class="active" width="50"></td>
                </tr>
            </thead>
            <tbody>
            @foreach($coins as $coin)
                <tr class="profile__row">
{{--                    <td class="active pad">--}}
{{--                        {{ ($coins->currentPage() * $coins->perPage()) - $coins->perPage() + $loop->iteration }}--}}
{{--                    </td>--}}
                    <td class="active {{ $coin->percent_change_1h > 0 ? 'green' : 'red' }}">
                        <div class="table__row d-flex" style="column-gap: 1rem; justify-content: space-between;">
                            <div class="table__flex table__flex_main d-flex">
                                @if(!empty($coin->info->logo))
                                    <img src="{{ $coin->info->logo }}" class="table__logo" alt="">
                                @endif
                                <p class="table__text">
                                    <a href="{{ route('coins.view', $coin->uuid) }}">
                                        <span>{{ $coin->name }}</span>
                                    </a>
                                    {{ $coin->symbol }}
                                </p>
                            </div>

                            <div class="table__flex d-flex" style="white-space: nowrap">
                                @include('blackshot::coins.partials.badge-position-text', ['percent' => $coin->percent_change_1h])
                            </div>
                        </div>
                    </td>

                    <td class="active">
                        <p class="table__el">
                            <strong>
                                @include('blackshot::coins.partials.price', ['price' => $coin->price])
                            </strong>
                        </p>
                    </td>

                    <td class="active">
                        {{ $coin->rank }}
                    </td>

                    <td class="active {{ $coin->rank_period > 0 ? 'green' : 'red' }}">
                        @include('blackshot::partials.badge-position-text', ['position' => $coin->rank_period])
                    </td>

                    <td class="active {{ $coin->rank_30d > 0 ? 'green' : 'red' }}">
                        @include('blackshot::partials.badge-position-text', ['position' => $coin->rank_30d])
                    </td>

                    <td class="active {{ $coin->rank_60d > 0 ? 'green' : 'red' }}">
                        @include('blackshot::partials.badge-position-text', ['position' => $coin->rank_60d])
                    </td>

                    <td class="active" data-label="">
                        <p class="table__el">
                            <div class="table__icons d-flex">
                                <a href="javascript:void(0);"
                                   onclick="return favorites(this, '{{ $coin->uuid }}')"
                                   class="table__star favorite {{ $favorites->where('uuid', $coin->uuid)->count() ? 'able' : '' }}">
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>

                                <a href="javascript:void(0);"
                                   onclick="return tracking(this, '{{ $coin->uuid }}')"
                                   class="table__icon tracking {{ $tracking->where('uuid', $coin->uuid)->count() ? 'able' : '' }}">
                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">
                                </a>
                            </div>

                        </p>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $coins->appends(request()->input())->links() }}

    <script>
        /**
         * Добавит монету в избранные
         * @param element
         * @param uuid
         */
        function favorites(element, uuid)
        {
            axios.post('{{ route('users.favorite') }}', {uuid})
                .then(response => {
                    if (response.data.data.favorite) {
                        element.classList.add('able')
                    } else {
                        element.classList.remove('able')
                    }
                })

            let tracking_link = element.parentElement.querySelector('.tracking')
            return tracking(tracking_link, uuid)
        }

        /**
         * Добавит монету в отслеживаемые
         * @param element
         * @param uuid
         */
        function tracking(element, uuid)
        {
            axios.post('{{ route('users.tracking') }}', { uuid })
                .then(response => {
                    if (response.data.data.tracking === 'delete') {
                        element.classList.remove('able')
                    } else {
                        element.classList.add('able')
                    }
                })
        }
    </script>
@endsection
