@extends('blackshot::layouts.app')

@section('content')
    <div class="scan__flex d-flex">
        <div class="scan__left">
            <h1 class="pages__title">
                Пользователи
            </h1>
        </div>
        <a href="{{ route('users.edit') }}" class="scan__show btn btn1">
            Добавить пользователя
        </a>
    </div>

    {{-- Filter form --}}
    <div class="scan__box users__box">
        <form action="{{ route('users.home') }}"
              method="POST"
              class="scan-form d-flex"
              id="filter_form"
              style="justify-content: flex-start; column-gap: 1rem"
        >
            @csrf
            <div class="scan-form__item">
                <label for="email">Email пользователя</label> <br>
                <input type="email"
                       id="email"
                       name="filter[email]"
                       value="{{ $filter['email'] ?? null }}"
                       onchange="return document.getElementById('filter_form').submit()"
                       inputmode="email"
                       placeholder="johnjohnson@gmail.com" class="mail">
            </div>
            <div class="scan-form__item">
                <label for="name">Имя пользователя</label><br>
                <input type="text"
                       id="name"
                       name="filter[name]"
                       value="{{ $filter['name'] ?? null }}"
                       onchange="return document.getElementById('filter_form').submit()"
                       placeholder="John Johnson"
                       class="name">
            </div>
            <div>
                <br>
                <button class="scan-form__btn btn btn2">
                    Фильтровать
                </button>
            </div>
        </form>
    </div>

    @if($users_without_tariff_count)
        <p class="alert" style="margin-bottom: 1rem; background-color: #dbeaff; color: #0a53be">
            У вас {{ $users_without_tariff_count }}
            {{ trans_choice('пользователь|пользователя|пользователей', $users_without_tariff_count) }}
            без тарифа, которые не имеют доступа к системе.
        </p>
    @endif

    <div class="scan__wrap">
        <table class="profile__table scan__table users__table adaptive-table">
            <thead>
                <tr class="profile__row">
                    <td class="active pad">Имя</td>
                    <td class="active">E-mail</td>
                    <td class="active">Роль</td>
                    <td class="active">Подписка</td>
                    <td class="active">Последнее обновление</td>
                    <td class="active"></td>
                </tr>
            </thead>

            <tbody class="signal-body">
                @foreach($users as $user)
                <tr class="profile__row">
                    <td class="active">
                        {{ $user->name }}
                    </td>

                    <td class="active">
                        {{ $user->email }}
                    </td>

                    <td class="active {{ $user->role }}">
                        <p>{{ $user->role == 'user' ? 'Пользователь' : 'Администратор' }}</p>
                    </td>

                    <td class="active">
                        @if ($user->tariff)
                            Тариф "{{ $user->tariff->name}}"<br>
                            <span style="font-weight: lighter; color: gray">
                                {{ $user->expired_at ? 'до ' .  \Illuminate\Support\Carbon::make($user->expired_at)->isoFormat('D MMMM YYYY') : '' }}
                            </span>
                        @else
                            ---
                        @endif
                    </td>

                    <td class="active">
                        <strong>{{ \Carbon\Carbon::createFromTimeString($user->created_at)->diffForHumans() }}</strong><br>
                        {{ $user->created_at->isoFormat('DD.MM.Y HH:mm') }}
                    </td>

                    <td class="active">
                        <div class="table__icons d-flex">
                            <a href="{{ route('users.edit', $user->id) }}" class="users__link d-flex">
                                <img src="{{ asset('css/img/edit.svg') }}" alt="" class="svg">
                                <p>Редактировать</p>
                            </a>

                            <form action="{{ route('users.delete') }}" method="post" class="users__link d-flex" onsubmit="return confirm('Подтердите удаление пользователя {{ $user->name }}.')">
                                @csrf
                                <input type="hidden" name="id" value="{{ $user->id }}">
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
    </div>

    {{ $users->links() }}
@endsection
