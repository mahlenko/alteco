@extends('blackshot::layouts.app')

@section('title', 'Тарифы')
@section('title-extend')
    <a href="{{ route('tariffs.edit') }}" class="btn btn2">
        Добавить тариф
    </a>
@endsection


@section('content')
    <table class="table">
        <thead class="table-secondary">
            <tr>
                <th>Название</th>
                <th>Стоимость</th>
                <th>Кол-во дней</th>
                <th>Подписок</th>
                <th>
                    <div class="flex-center">
                        <span>По-умолчанию</span>
                        <span class="ignore-offsets" title="Тариф будет выбран при самостоятельной регистрации пользователя в проекте. Только 1 тарифный план может быть тарифом по-умолчанию.">
                            <svg xmlns="http://www.w3.org/2000/svg" class="table-icon opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                </th>
                <th>
                    <div class="flex-center">
                        <span>Бесплатный</span>
                        <span class="ignore-offsets" title="К тарифу будут применены ограничения бесплатного тарифа.">
                            <svg xmlns="http://www.w3.org/2000/svg" class="table-icon opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    </div>
                </th>
                <th>Создан</th>
                <th>Обновлен</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($tariffs as $tariff)
            <tr>
                <td>
                    <a href="{{ route('tariffs.edit', $tariff) }}" class="link">
                        <span>{{ $tariff->name }}</span>
                    </a>
                </td>
                <td>{{ $tariff->amount }}</td>
                <td>{{ $tariff->days }}</td>
                <td>{{ $tariff->subscribes_count }}</td>
                <td>
                    <svg xmlns="http://www.w3.org/2000/svg" class="table-icon {{ $tariff->isDefault() ? 'success' : 'secondary' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </td>
                <td>
                    <svg xmlns="http://www.w3.org/2000/svg" class="table-icon {{ $tariff->isFree() ? 'success' : 'secondary' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </td>
                <td>{{ $tariff->created_at->isoFormat('D MMMM YYYY в H:mm') }}</td>
                <td>{{ $tariff->updated_at?->isoFormat('D MMMM YYYY в H:mm') }}</td>
                <td>
                    <div class="flex-center gap-x-2">
                        <a href="{{ route('tariffs.edit', $tariff) }}" class="users__link d-flex">
                            <img src="{{ asset('css/img/edit.svg') }}" alt="" class="svg">
                            <p>Редактировать</p>
                        </a>

                        <form action="{{ route('tariffs.delete') }}"
                              method="post"
                              class="users__link d-flex"
                              onsubmit='return confirm("Тариф \"{{ $tariff->name }}\" невозможно будет восстановить, а подписка пользователей обнулится.\n\nХотите продолжить?")'
                        >
                            @csrf
                            @method('delete')
                            <input type="hidden" name="id" value="{{ $tariff->id }}">
                            <button type="submit" class="users__link d-flex">
                                <img src="{{ asset('css/img/delete.svg') }}" alt="" class="svg">
                                <p>Удалить</p>
                            </button>
                        </form>

                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
