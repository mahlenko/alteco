@extends('blackshot::layouts.app')

@section('content')
    <div class="scan__flex d-flex">
        <div class="scan__left">
            <h1 class="pages__title">
                Настройки системы
            </h1>
        </div>
    </div>

    <form action="{{ route('settings.store') }}" method="post">
        @csrf
        <table class="table-setting">
            <thead class="table-secondary">
                <tr>
                    <th>Key</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <label for="api_key">API Key</label><br>
                        <a href="https://pro.coinmarketcap.com/account" target="_blank">Get Api Key</a>
                    </td>
                    <td>
                        <input type="text"
                               value="{{ old('api_key', $settings['api_key'] ?? null) }}"
                               name="api_key"
                               id="api_key"
                               size="40"
                               placeholder="********-****-****-****-************">
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="loading_coins_position">Number of positions of loaded coins</label>
                    </td>
                    <td>
                        <input type="text"
                               value="{{ old('loading_coins_position', $settings['loading_coins_position'] ?? null) }}"
                               name="loading_coins_position"
                               id="loading_coins_position"
                               size="40"
                               placeholder="100">
                    </td>
                </tr>
            </tbody>
        </table>

        <p>
            <button type="submit" class="btn btn2">Сохранить</button>
        </p>
    </form>
@endsection
