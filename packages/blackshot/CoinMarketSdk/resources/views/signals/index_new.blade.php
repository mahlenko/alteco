@extends('blackshot::layouts.app')

@section('content')
    <div class="scan__flex d-flex">
        <div class="scan__left">
            <h1 class="pages__title">
                Сигналы
            </h1>
            <p class="scan__search">
                Всего {{ $signals->count() }}
                {{ \Illuminate\Support\Str::plural('signal', $signals->count()) }}
            </p>
        </div>
        <a href="{{ route('coins.home') }}" class="scan__show btn btn1">
            Добавить монеты
        </a>
    </div>

    {{-- filter --}}
    <div class="scan__box">
        <form action="{{ route('signals.filter.store') }}" method="POST" class="scan-form" id="filter_form">
            @csrf

            {{-- General --}}
            <div class="scan-form__row">
                <div class="scan-form__item">
                    <label>Количество мест</label><br>
                    <input type="number"
                           name="filter[min_rank]"
                           value="{{ $filter->min_rank }}"
                           onchange="return document.getElementById('filter_form').submit()"
                           placeholder="30">

                    <div class="signal__block">за</div>
                </div>

                <div class="scan-form__item">
                    <label>Количество дней</label><br>
                    <input type="number"
                           name="filter[days]"
                           value="{{ $filter->days }}"
                           onchange="return document.getElementById('filter_form').submit()"
                           placeholder="7">
                    <div class="signal__block">дней</div>
                </div>

                <div class="scan-form__item">
                    <label>Категория</label> <br>
                    <select name="filter[categories_uuid][]" multiple class="select" id="category">
                        @foreach($categories as $value => $label)
                            @if ($label instanceof \Illuminate\Support\Collection)
                                <optgroup label="{{ __('categories.'.$value) }}">
                                    @foreach($label as $val => $text)
                                        <option
                                            @if(isset($filter->categories_uuid) && in_array($val, $filter->categories_uuid))
                                                selected
                                            @endif
                                            value="{{ $val }}"
                                        >{{ $text }}</option>
                                    @endforeach
                                </optgroup>
                            @else
                            <option
                                @if(isset($filter->categories_uuid) && in_array($value, $filter->categories_uuid))
                                    selected
                                @endif
                                value="{{ $value }}"
                            >{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <br>
                    <button class="scan-form__btn btn btn2">
                        Применить
                    </button>
                </div>
            </div>

            {{-- Checkboxed --}}
            <div class="scan-form__row signal__bottom d-flex">
                <p class="signal__text">Выберите сигналы:</p>

                <div class="d-flex opt">
                    <input type="checkbox"
                           name="filter[signals][]"
                           onchange="return document.getElementById('filter_form').submit()"
                           {{ array_search('signal_max_diff', $filter->signals) !== false ? 'checked' : null }}
                           value="signal_max_diff"
                           id="signal_max_diff"
                    >
                    <label for="signal_max_diff">
                        <p>
                            Самый большой прирост в последнем обновлении
                            @if ($smt = $signals->where('signal_max_diff')->count())
                                <span>
                                ({{ $smt }}
                                    {{ \Illuminate\Support\Str::plural('coin', $smt) }})
                            </span>
                            @endif
                        </p>
                    </label>
                </div>
                <div class="d-flex opt">
                    <input type="checkbox"
                           name="filter[signals][]"
                           onchange="return document.getElementById('filter_form').submit()"
                           {{ array_search('signal_max_period', $filter->signals) !== false ? 'checked' : null }}
                           id="signal_max_period"
                           value="signal_max_period"
                    >
                    <label for="signal_max_period">
                        <p>
                            Самый большой прирост за {{ $filter->days }} дней
                            @if ($smp = $signals->where('signal_max_period')->count())
                                <span>
                                ({{ $smp }}
                                    {{ \Illuminate\Support\Str::plural('coin', $smp) }})
                            </span>
                            @endif
                        </p>
                    </label>
                </div>

                <div class="d-flex opt">
                    <input type="checkbox"
                           name="filter[signals][]"
                           onchange="return document.getElementById('filter_form').submit()"
                           {{ array_search('signal_more_change_rank', $filter->signals) !== false ? 'checked' : null }}
                           id="signal_more_change_rank"
                           value="signal_more_change_rank"
                    >
                    <label for="signal_more_change_rank">
                        <p>
                            {{ $filter->min_rank }} и больше позиций за период
                            @if ($smcr = $signals->where('signal_more_change_rank')->count())
                                <span>
                                ({{ $smcr }}
                                    {{ \Illuminate\Support\Str::plural('coin', $smcr) }})
                            </span>
                            @endif
                        </p>
                    </label>
                </div>
            </div>

        </form>
    </div>
    {{-- end: filter --}}

    @if ($coins->count())
        @include('blackshot::signals.partials.table_new', ['coins' => $coins, 'table_name' => 'signals'])
    @else
        <div class="alert alert-info">
            @if (!\Illuminate\Support\Facades\Auth::user()->favorites->count())
                <p>
                    <i class="fas fa-info-circle"></i>
                    <strong>Список монет пуст.</strong>
                </p>
                <p class="mb-0">
                    <a href="{{ route('coins.home') }}">Добавить монеты</a> которые вас интересуют, чтобы отслеживать сигналы по ним.
                </p>
            @else
                <p class="mb-0">
                    <i class="fas fa-info-circle"></i>
                    @if ($filter->signals)
                        Монеты с таким сигналом не найдены.
                    @else
                        Выберите сигналы, которые вы хотите отобразить.
                    @endif
                </p>
            @endif
        </div>
    @endif

    @if($coins_buying_me->count())
        <h2 class="mt-5 mb-3">Покупаю</h2>
        @include('blackshot::signals.partials.table_new', ['coins' => $coins_buying_me, 'table_name' => 'buying'])
    @endif
@endsection
