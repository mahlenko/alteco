@extends('blackshot::layouts.app')

@section('content')
    <div class="d-flex align-items-center justify-content-between px-2 mb-3">
        <div>
            <h1>
                <i class="fas fa-chess-queen text-secondary" aria-hidden="true"></i>
                <strong>Signals</strong>
            </h1>
        </div>

        <a href="{{ route('coins.home') }}" class="btn btn-outline-success">
            <i class="fab fa-bitcoin"></i>
            Add coins for tracking
        </a>
    </div>

    <div class="mb-4">
        <form action="{{ route('signals.filter.store') }}" method="POST" id="filter_form">
            @csrf
            <div class="rounded p-3 border bg-light">
                <div class="d-flex flex-column flex-md-row">
                    {{--  --}}
                    <div class="col-12 col-md-5">
                        <label class="mb-2" for="positions">
                            <strong>Number of seats</strong>
                        </label>

                        <div class="input-group">
                            <input
                                id="positions"
                                name="filter[min_rank]"
                                type="text"
                                value="{{ $filter->min_rank }}"
                                onchange="return document.getElementById('filter_form').submit()"
                                class="form-control d-inline-block">

                            <label class="input-group-text" for="days">for</label>

                            <input
                                id="days"
                                type="text"
                                name="filter[days]"
                                value="{{ $filter->days }}"
                                onchange="return document.getElementById('filter_form').submit()"
                                class="form-control">

                            <label class="input-group-text" for="days">days</label>
                        </div>
                    </div>

                    {{-- --}}
                    <div class="col-12 col-md-4 mb-1 mx-1 ms-md-3">
                        <label for="category" class="mb-2">
                            <strong>Categories</strong>:
                        </label>

                        <select name="filter[categories_uuid][]" multiple id="category">
                            @foreach($categories as $value => $label)
                                <option
                                    @if(isset($filter->categories_uuid) && in_array($value, $filter->categories_uuid))
                                        selected
                                    @endif
                                    value="{{ $value }}"
                                    >{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{--  --}}
                <ul class="list-inline mt-2 mb-0">
                    <li class="list-inline-item">
                        <strong>
                            Select signals:
                        </strong>
                    </li>

                    <li class="list-inline-item me-3">
                        <input type="checkbox"
                               name="filter[signals][]"
                               class="form-check-input border-primary checkbox-primary"
                               onchange="return document.getElementById('filter_form').submit()"
                               {{ array_search('signal_max_diff', $filter->signals) !== false ? 'checked' : null }}
                               id="signal_max_diff"
                               value="signal_max_diff">

                        <label class="form-check-label ms-1" for="signal_max_diff">
                            <small>
                                The biggest increase in a last update
                                @if ($smt = $signals->where('signal_max_diff')->count())
                                    <span class="text-secondary">
                                        ({{ $smt }}
                                        {{ \Illuminate\Support\Str::plural('coin', $smt) }})
                                    </span>
                                @endif
                            </small>
                        </label>
                    </li>

                    <li class="list-inline-item me-3">
                        <input type="checkbox"
                               name="filter[signals][]"
                               class="form-check-input border-warning checkbox-warning"
                               onchange="return document.getElementById('filter_form').submit()"
                               {{ array_search('signal_max_period', $filter->signals) !== false ? 'checked' : null }}
                               id="signal_max_period"
                               value="signal_max_period">

                        <label class="form-check-label ms-1" for="signal_max_period">
                            <small>
                                The biggest increase in {{ $filter->days }} days
                                @if ($smp = $signals->where('signal_max_period')->count())
                                    <span class="text-secondary">
                                        ({{ $smp }}
                                        {{ \Illuminate\Support\Str::plural('coin', $smp) }})
                                    </span>
                                @endif
                            </small>
                        </label>
                    </li>

                    <li class="list-inline-item me-3">
                        <input type="checkbox"
                               name="filter[signals][]"
                               class="form-check-input border-success checkbox-success"
                               onchange="return document.getElementById('filter_form').submit()"
                               {{ array_search('signal_more_change_rank', $filter->signals) !== false ? 'checked' : null }}
                               id="signal_more_change_rank"
                               value="signal_more_change_rank">

                        <label class="form-check-label ms-1" for="signal_more_change_rank">
                            <small>
                                {{ $filter->min_rank }} or more positions per period
                                @if ($smcr = $signals->where('signal_more_change_rank')->count())
                                    <span class="text-secondary">
                                        ({{ $smcr }}
                                        {{ \Illuminate\Support\Str::plural('coin', $smcr) }})
                                    </span>
                                @endif
                            </small>
                        </label>
                    </li>
                </ul>

                <button type="submit" class="btn btn-primary mt-2">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
            </div>
        </form>
    </div>

    <p>
        Total of {{ $signals->count() }}
        {{ \Illuminate\Support\Str::plural('signal', $signals->count()) }}
    </p>

    @if ($coins->count())
        @include('blackshot::signals.partials.table_new', ['coins' => $coins, 'table_name' => 'signals'])
    @else
        <div class="alert alert-info">
            @if (!\Illuminate\Support\Facades\Auth::user()->favorites->count())
                <p>
                    <i class="fas fa-info-circle"></i>
                    <strong>The list of currencies is empty.</strong>
                </p>
                <p class="mb-0">
                    <a href="{{ route('coins.home') }}">Add the currencies</a> you are interested in for tracking to your favorites list.
                </p>
            @else
                <p class="mb-0">
                    <i class="fas fa-info-circle"></i>
                    @if ($filter->signals)
                        Coins with such a signal filter have not been found.
                    @else
                        Select the signal or signals above that you want to display.
                    @endif
                </p>
            @endif
        </div>
    @endif

    @if($coins_buying_me->count())
        <h2 class="mt-5 mb-3">Buying me</h2>
        @include('blackshot::signals.partials.table_new', ['coins' => $coins_buying_me, 'table_name' => 'buying'])
    @endif
@endsection
