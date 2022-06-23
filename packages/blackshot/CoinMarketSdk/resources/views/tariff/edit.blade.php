@extends('blackshot::layouts.app')

@section('title', 'Настройка тарифа')

@section('content')
    <form action="{{ route('tariffs.store') }}" method="post">
        @csrf

        <input type="hidden" name="id" value="{{ $tariff->id ?? null }}">

        <table class="table-setting">
            <tbody>
                <tr>
                    <td>
                        <label for="name">Название</label>
                    </td>
                    <td>
                        <input type="text"
                               value="{{ old('name', $tariff?->name ?? null) }}"
                               name="name"
                               id="name"
                               size="40"
                               placeholder="Тариф 1">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="amount">Стоимость</label>
                    </td>
                    <td>
                        <input type="text"
                               value="{{ old('amount', $tariff->amount ?? 0) }}"
                               name="amount"
                               id="amount"
                               size="40"
                               placeholder="0">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="days">Количество дней (подписка)</label>
                    </td>
                    <td>
                        <input type="text"
                               value="{{ old('days', $tariff->days ?? null) }}"
                               name="days"
                               id="days"
                               size="40"
                               placeholder="10">
                    </td>
                </tr>

                <tr>
                    <td>
                        Тариф по-умолчанию
                    </td>
                    <td style="line-height: 1rem !important;">
                        <input type="checkbox"
                            id="default"
                            name="default"
                            value="1"
                            {{ old('default', $tariff->default ?? 0) ? 'checked' : null }}
                        />
                        <label for="default">Да</label><br>

                        <small style="display: block; margin-top: .5rem;">
                            Только 1 тариф, может быть тарифом по-умолчанию.<br><br>
                            Тариф будет назначен пользователю, если не передали<br>
                            ID тарифа через API или пользователь зарегистрировался с главной страницы.
                        </small>
                    </td>
                </tr>

                <tr>
                    <td>
                        Тариф бесплатный
                    </td>
                    <td style="line-height: 1rem !important;">
                        <input type="checkbox"
                               id="free"
                               name="free"
                               value="1"
                            {{ old('free', $tariff->free ?? 0) ? 'checked' : null }}
                        />
                        <label for="free">Да</label><br>

                        <small style="display: block; margin-top: .5rem;">
                            Для таких тарифов будут действовать ограничения функционала.<br>
                            Как пример, можно использовать для отслеживания рекламных компаний.
                        </small>
                    </td>
                </tr>

                <tr>
                    <td>
                        Назначить пользователям
                    </td>
                    <td style="line-height: 1rem !important;">
                        <select name="move" id="move">
                            <option value="{{ \Blackshot\CoinMarketSdk\Repositories\TariffRepository::NOT_MOVE }}">Не выбрано</option>
                            <option value="-1">Всем пользователям</option>
                            <option value="0">Пользователи без тарифа</option>
                            @if ($tariffs)
                            <optgroup label="Тарифы">
                                @foreach ($tariffs as $tariff)
                                    <option value="{{ $tariff->id }}">{{ $tariff->name }}</option>
                                @endforeach
                            </optgroup>
                            @endif
                        </select>
                        <small style="display: block; margin-top: .5rem;">
                            Выберите группу пользователей которым хотите назначить этот тариф.<br>
                            <strong>(не обязательно)</strong>
                        </small>
                    </td>
                </tr>
            </tbody>
        </table>

        <p>
            <button type="submit" class="btn btn2">Сохранить</button>
        </p>
    </form>
@endsection
