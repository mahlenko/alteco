@extends('blackshot::layouts.modal', [
    'title' => "{$coin->name}",
    'titleIconUrl' => $coin->info->logo
])

@section('form-body')
    @if ($transactions->count())

    <table class="modal-table" id="data" style="width: 500px">
        <thead>
            <tr>
                <th>Тип</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th>Fee</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $item)
                <tr data-id="{{ $item->getKey() }}">
                    <td>
                        {{ $item->type->name }}
                        {{ $item->transfer_type?->name }}
                    </td>
                    <td>
                        <b>{{ $item->quantity }} {{ $coin->symbol }}</b><br>
                        <small>{{ $item->date_at->isoFormat('D MMMM g', 'Do MMMM') }}</small>
                    </td>
                    <td>${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->quantity * $item->price) }}</td>
                    <td>${{ \Blackshot\CoinMarketSdk\Helpers\NumberHelper::format($item->fee) }}</td>
                    <td>
                        <form action="{{ route('api.portfolio.transaction.delete') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="uuid" value="{{ $item->getKey() }}">
                            <input type="hidden" name="portfolio_id" value="{{ $item->portfolio_id }}">
                            <input type="hidden" name="coin_uuid" value="{{ $coin->getKey() }}">
                            <input type="hidden" name="user_id" value="{{ $item->user_id }}">
                            <button class="case-table__link">
                                <svg class="icon"><use xlink:href="css/img/svg-sprite.svg#table4"></use></svg>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <script>
            function formSuccessHandle(response) {
                let table = document.querySelector('#data')
                table.querySelector('tbody tr[data-id="'+ response.uuid +'"]').remove()

                if (table.querySelectorAll('tbody tr').length === 1) {
                    window.location.reload()
                }
            }
        </script>

    @else
        <p>
            Еще нет информации о стейкинге.<br>
            Добавьте стейкинг, в столбце "Стейкинг" нажмите "+".
        </p>
    @endif
@endsection
