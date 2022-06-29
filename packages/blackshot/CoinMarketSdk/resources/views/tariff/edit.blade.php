@extends('blackshot::layouts.app')

@section('title', 'Настройка тарифа')

@section('content')
    <div class="row">
        <div class="column">
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
                                        @foreach ($tariffs as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
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
        </div>

        <div class="banner-container column">
            <div class="flex justify-between items-center">
                <h3>Баннеры</h3>
                <span>
                    <a href="{{ route('tariffs.banners.edit', ['tariff' => $tariff]) }}" class="btn btn2">
                        Добавить баннер
                    </a>
                </span>
            </div>

            <div class="banners">
                @if ($tariff->banners && $tariff->banners->count())
                    @foreach($tariff->banners as $banner)
                        <div class="item">
                            @if (!empty($banner->picture))
                                <img src="{{ $banner->pictureUrl() }}" class="picture" alt="">
                            @endif

                            <div>
                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($banner->body), 30) }}</p>
                                <ul class="list-info">
                                    <li>Старт: {{ $banner->start->isoFormat('DD MMMM YYYY') }}</li>
                                    <li>Стоп: {{ $banner->end?->isoFormat('DD MMMM YYYY') ?? '---' }}</li>
                                    <li>Просмотров: {{ $banner->views }}</li>
                                    @if (!$banner->is_active)
                                    <li style="color: red; display: flex; align-items: center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        Черновик
                                    </li>
                                    @endif
                                </ul>
                                <div style="display: flex; column-gap: 1rem">
                                    <a href="{{ route('tariffs.banners.edit', ['tariff' => $tariff, 'banner' => $banner]) }}">Редактировать</a>

                                    <form
                                        action="{{ route('tariffs.banners.delete') }}"
                                        method="post"
                                        onsubmit="return confirm('Действительно удалить баннер?')"
                                    >
                                        @csrf
                                        <input type="hidden" name="uuid" value="{{ $banner->uuid }}">
                                        <button class="users__link" style="color: orangered">Удалить</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="item">
                        Рекламных банеров в этом тарифе еще нет, но вы можете их добавить.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .flex {
            display: flex;
            width: 100%;
        }

        .flex.justify-between {
            justify-content: space-between;
        }
        .flex.items-center {
            align-items: center;
        }

        .row {
            display: flex;
            flex-direction: column;
            row-gap: 4rem;
            column-gap: 4rem;
        }

        .row .column {
            max-width: 100%;
        }

        .banner-container {
            /*border: 1px solid #eee;*/
            display: flex;
            flex-direction: column;
            row-gap: 1rem;
            border-radius: 1rem;
            background-color: #f7f7f7;
            padding: 1rem 1.5rem;
            width: 100%;
        }

        .banners .item {
            align-items: flex-start;
            background-color: white;
            border: 1px solid #f4f4f4;
            border-radius: 1rem;
            display: flex;
            column-gap: 1rem;
            padding: .5rem;
        }

        .banners .item .picture {
            border-radius: .6rem;
            max-height: 70px;
            /*width: 25%;*/
            max-width: 25%;
        }

        .banners .icon {
            width: 1rem;
            height: 1rem;
        }

        .banners .item .list-info {
            display: flex;
            flex-wrap: wrap;
            column-gap: 1rem;
            margin: .3rem 0;
            padding: 0;
        }

        .banners .item .list-info li {
            color: slategray;
            font-size: .8rem;
        }

        @media screen and (min-width: 1024px) {
            .row {
                flex-direction: row;
            }

            .banner-container {
                padding: 1rem 2rem;
                width: 50%;
            }

            .banners .item .picture {
                max-width: 15%;
            }
        }
    </style>
@endsection
