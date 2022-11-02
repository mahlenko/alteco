@extends('blackshot::layouts.modal-form', [
    'title' => "{$coin->name}",
    'titleIconUrl' => $coin->info->logo,
    'form' => route('api.portfolio.stacking.create'),
    'formClass' => 'portfolio-form',
])

@push('footer', '<button type="submit" class="btn btn1">Добавить cтейкинг</button>')

@section('form-body')
    <input type="hidden" name="user_id" data-cast="integer" value="{{ Auth::id() }}" />
    <input type="hidden" name="portfolio_id" data-cast="integer" value="{{ $portfolio->getKey() }}" />
    <input type="hidden" name="coin_uuid" value="{{ $coin->getKey() }}" />

    <div class="fields-row">
        <div class="field-control">
            <label for="amount" class="global-flex between">
                <span>Количество</span>
                <small style="font-weight: normal; color: slategrey; margin-left: .5rem;">
                    макс.: {{ $portfolio_coin->quantity() - $portfolio_coin->stacking->quantity() }}
                    {{ $coin->symbol }}
                </small>
            </label>
            <input type="text"
                   required
                   data-type="number"
                   data-cast="float"
                   id="amount"
                   name="amount"
                   size="32"
                   autofocus
                   placeholder="Количество"
            />
        </div>
    </div>

    <div class="fields-row">
        <div class="field-control">
            <label for="apy" style="display: flex; align-items: center;">
                <span>APY, %</span>
                <span style="display: flex; margin-left: .4rem; cursor: help" title="Годовая доходность актива">
                    <svg style="width: 1.2rem;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                </span>
            </label>
            <input type="text"
                   required
                   data-type="number"
                   data-cast="float"
                   value=""
                   id="apy"
                   name="apy"
                   maxlength="3"
                   size="15"
            />
        </div>

        <div class="field-control">
            <label for="stacking_at">Дата</label>
            <input id="stacking_at"
                   required
                   name="stacking_at"
                   size="10"
                   data-type="datepicker">
        </div>
    </div>
@endsection
