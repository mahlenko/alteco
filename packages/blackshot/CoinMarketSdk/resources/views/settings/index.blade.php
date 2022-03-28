@extends('blackshot::layouts.app')

@section('content')

    <div class="d-flex align-items-center justify-content-between px-2 mb-3">
        <div>
            <h1>
                <i class="fas fa-cog text-secondary" aria-hidden="true"></i>
                <strong>Settings</strong>
            </h1>
        </div>
    </div>

    <form action="{{ route('settings.store') }}" method="post">
        @csrf
        <table class="table">
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
                               class="form-control"
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
                               class="form-control"
                               placeholder="100">
                    </td>
                </tr>
            </tbody>
        </table>

        <p>
            <button type="submit" class="btn btn-outline-success">
                <i class="far fa-save"></i>
                Save settings
            </button>
        </p>
    </form>
@endsection
