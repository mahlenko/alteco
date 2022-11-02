@extends('blackshot::layouts.modal', [
    'title' => "{$coin->name}",
    'titleIconUrl' => $coin->info->logo
])

@push('footer', '<button type="submit" class="btn btn1">Добавить cтейкинг</button>')

@section('form-body')
    @if ($stacking->count())
    <table class="modal-table" id="data">
        <thead>
            <tr>
                <th>Стейкинг</th>
                <th>APY, %</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($stacking as $item)
                <tr data-id="{{ $item->getKey() }}">
                    <td>
                        <b>{{ $item->amount }} {{ $coin->symbol }}</b><br>
                        <small>{{ $item->stacking_at->isoFormat('D MMMM g', 'Do MMMM') }}</small>
                    </td>
                    <td>{{ $item->apy }}%</td>
                    <td>
                        <form action="{{ route('api.portfolio.stacking.delete') }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id" value="{{ $item->getKey() }}">
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
                table.querySelector('tbody tr[data-id="'+ response.id +'"]').remove()

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
