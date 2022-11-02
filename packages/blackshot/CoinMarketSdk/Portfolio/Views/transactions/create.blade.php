@extends('blackshot::layouts.modal-form', [
    'title' => 'Добавить транзакцию',
    'form' => route('api.portfolio.transaction.create'),
    'formClass' => 'portfolio-form',
])

@push('footer', '<button type="submit" class="btn btn1">Добавить транзакцию</button>')

@section('form-body')
    <input type="hidden" name="user_id" data-cast="integer" value="{{ Auth::id() }}" />
    <input type="hidden" name="portfolio_id" data-cast="integer" value="{{ $portfolio->getKey() }}" />

    <ul class="tabs-radio">
        @foreach(\Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum::cases() as $case)
        <li class="tab" onclick="this.querySelector('input').checked=true">
            <input type="radio" id="type_{{ $case->name }}" onclick="return toggleType(this)" name="type" {{ (isset($transaction) && $case->name == $transaction->type) || (!isset($transaction) && $case == \Blackshot\CoinMarketSdk\Portfolio\Enums\TransactionTypeEnum::Buy) ? 'checked' : null }} value="{{ $case->name }}">
            <label for="type_{{ $case->name }}">{{ $trans['transaction'][$case->name] }}</label>
        </li>
        @endforeach
    </ul>

    <select name="coin_uuid" id="coin" class="select" data-ui="selectize" onchange="return priceUpdate()">
        @foreach($coins as $coin)
            <option value="{{ $coin->uuid }}"
                    data-order="{{ $coin->rank }}"
                    data-icon="{{ $coin->info?->logo }}"
                    data-text="<b>{{ $coin->name }}</b> <span class='text-secondary'>{{ $coin->symbol }}</span>"
            >{{ $coin->name }}
            </option>
        @endforeach
    </select>

    <div class="field-control" style="display: none" data-group="transfer">
        <label for="quantity">Тип транзакции</label>
        <select name="transfer_type" id="transfer_type" class="select" data-ui="selectize">
            @foreach(\Blackshot\CoinMarketSdk\Portfolio\Enums\TransferTypeEnum::cases() as $case)
                <option value="{{ $case->name }}">{{ $case->name == 'In' ? 'Получено' : 'Отправлено' }}</option>
            @endforeach
        </select>
    </div>

    <div class="fields-row">
        <div class="field-control">
            <label for="quantity">Количество</label>
            <input type="text"
                   data-type="number"
                   data-cast="float"
                   value=""
                   id="quantity"
                   name="quantity"
                   autofocus
                   onfocus="return totalUpdate()"
                   onkeyup="return totalUpdate()"
                   placeholder="Количество"
            />
        </div>

        <div class="field-control" data-group="market">
            <label for="price">Цена</label>
            <input type="text"
                   data-type="price"
                   data-cast="float"
                   value="{{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($coins->first()->price) }}"
                   id="price"
                   name="price"
                   onchange="return totalUpdate()"
                   onblur="return totalUpdate()"
                   placeholder="Цена"
            />
        </div>
    </div>

    <div class="fields-row">
        <div class="field-control">
            <label for="date_at">Дата</label>
            <input id="date_at"
                   name="date_at"
                   onchange="return priceUpdate()"
                   data-type="datepicker">
        </div>

        <div class="field-control">
            <label for="fee">Fee</label>
            <input type="text"
                   data-type="number"
                   data-cast="float"
                   value=""
                   id="fee"
                   name="fee"
                   onkeyup="return totalUpdate()"
                   maxlength="10"
                   size="4"
                   placeholder="Цена"
            />
        </div>
    </div>

    <div class="portfolio-total" data-group="market">
        <span class="text-secondary">Всего потрачено</span>
        <h4>$ <span id="total">0</span></h4>
    </div>

    <script>

        function toggleType(radio)
        {
            let form = document.querySelector('form.portfolio-form')
            let type = radio.value

            if (radio.value === 'Transfer') {
                form.querySelectorAll('[data-group="transfer"]').forEach(item => item.style.display = null)
                form.querySelectorAll('[data-group="market"]').forEach(item => item.style.display = 'none')
            } else {
                form.querySelectorAll('[data-group="transfer"]').forEach(item => item.style.display = 'none')
                form.querySelectorAll('[data-group="market"]').forEach(item => item.style.display = null)
            }

        }

        /**
         * Calculate total spent
         * @returns {boolean}
         */
        function totalUpdate()
        {
            let form = document.querySelector('form.portfolio-form')
            if (!form) return true

            let quantity = number.parseFloat(form.querySelector('#quantity').value)
            let price = number.parseFloat(form.querySelector('#price').value)
            let fee = number.parseFloat(form.querySelector('#fee').value)

            let total = form.querySelector('#total')

            total.textContent = ((quantity * price) + fee).toFixed(2)
        }

        /**
         * Get price
         */
        function priceUpdate()
        {
            let form = document.querySelector('form.portfolio-form')
            let quantity = form.querySelector('#quantity')

            let coin_uuid = form.querySelector('#coin').value ?? null
            let date = form.querySelector('#date_at').value ?? null

            let price = form.querySelector('#price')

            if (!coin_uuid) return false

            axios.get('{{ route('portfolio.data.price') }}', {
                params: {coin_uuid, date}
            }).then(response => {
                if (response.data.ok) {
                    price.value = response.data.data.price.replace('.', ',')
                    price.dispatchEvent(new Event('updateMask'))

                    quantity.focus()
                    quantity.selectionStart = quantity.value.length;
                } else {
                    console.error(response.data)
                }
            })
        }

        const number = {
            parseFloat: value => {
                value = value.trim().replace(',', '.');

                if (!value || value === '.') return 0
                let result = parseFloat(value)

                if (isNaN(result)) {
                    value = value.replace(/[^0-9.]/g, '')
                    if (!value || value === '.') return 0

                    return parseFloat(value)
                }

                return result
            }
        }
    </script>

    <style>
        .portfolio-total {
            border-radius: .5rem;
            background-color: #eaeff6;
            padding: 1rem;
        }

        .portfolio-total h4 {
            font-size: 1.6rem;
            line-height: 130%;
            margin: 0;
        }

        .portfolio-total .text-secondary {
            color: slategrey;
        }
    </style>
@endsection
