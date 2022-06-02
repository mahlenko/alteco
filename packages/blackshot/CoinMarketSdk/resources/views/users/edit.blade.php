@extends('blackshot::layouts.app')

@section('content')

    <section class="settings" id="settings">
        <div class="container">
            <h1 class="pages__title">
                Настройки профиля
            </h1>

            <div class="settings__box d-flex" style="margin-top: 2rem;">
                @if (\Illuminate\Support\Facades\Auth::id() == $user->id)
                <div class="settings__acc">
                    <div class="settings__person">
                        <img src="https://www.gravatar.com/avatar/{{ md5($user->email.'?s=60&d=identicon') }}" alt="" class="settings__ava">
                        <p class="settings__name">
                            {{ $user->name }}
                        </p>
                    </div>
                    <ul class="settings__list">
                        <li class="d-flex active">
                            <img src="{{ asset('css/img/settings/1.svg') }}" alt="" class="settings__icon svg">
                            <a href="{{ route('users.edit', \Illuminate\Support\Facades\Auth::id()) }}" class="settings__text">
                                Профиль
                            </a>
                        </li>
                        <li class="d-flex">
                            <img src="{{ asset('css/img/settings/2.svg') }}" alt="" class="settings__icon svg">
                            <a href="{{ route('coins.home') }}" class="settings__text">
                                Монеты
                            </a>
                        </li>
                        <li class="d-flex">
                            <img src="{{ asset('css/img/settings/3.svg') }}" alt="" class="settings__icon svg">
                            <a href="{{ route('signals.home') }}" class="settings__text">
                                Сигналы
                            </a>
                        </li>
                        <li class="d-flex">
                            <img src="{{ asset('css/img/settings/4.svg') }}" alt="" class="settings__icon svg">
                            <a href="{{ route('users.home') }}" class="settings__text">
                                Пользователи
                            </a>
                        </li>
                        <li class="d-flex">
                            <img src="{{ asset('css/img/settings/5.svg') }}" alt="" class="settings__icon svg">
                            <a href="javascript:void(0);" class="settings__text" onclick="return alert('Функция в разработке, попробуйте позже.')">
                                Тарифы
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="scan__show btn btn1 settings__btn" onclick="return alert('Функция в разработке, попробуйте позже.')">
                                Улучшить тариф
                            </a>
                        </li>

                        <li class="d-flex">
                            <img src="{{ asset('css/img/settings/6.svg') }}" alt="" class="settings__icon svg">
                            <a href="javascript:void(0);" class="settings__text" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Выйти
                            </a>
                        </li>
                    </ul>
                </div>
                @endif

                {{-- форма --}}
                <div class="settings__info">
                    <form action="{{ route('users.store') }}" method="post">
                        @csrf

                        @if(isset($user))
                            <input type="hidden" name="id" value="{{ $user->id }}">
                        @endif

                        <div class="settings__el">
                            <div class="setting-form__item">
                                <label>Имя</label><br>
                                <input type="text" name="name" value="{{ old('name', isset($user) ? $user->name : null) }}" required placeholder="John Johnson" class="name">
                            </div>

                            <div class="setting-form__item">
                                <label>Email</label> <br>
                                <input type="email" name="email" inputmode="email" value="{{ old('name', isset($user) ? $user->email : null) }}" required placeholder="johnjohnson@gmail.com" class="mail">
                            </div>
                        </div>

                        <div class="settings__el">
                            <div class="setting-form__item">
                                <label>Новый пароль</label> <br>
                                <input type="password" name="password" placeholder="****************" class="pass">
                            </div>
                        </div>

                        @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                            <div class="settings__el">
                                <div class="setting-form__item">
                                    <label>Роль</label> <br>
                                    <select name="role" id="role">
                                        <option value="{{ \App\Models\User::ROLE_USER }}" @if ((isset($user) && old('role', $user->role) === \App\Models\User::ROLE_USER) || old('role') === \App\Models\User::ROLE_USER) selected @endif>User</option>
                                        <option value="{{ \App\Models\User::ROLE_ADMIN }}" @if ((isset($user) && old('role', $user->role) === \App\Models\User::ROLE_ADMIN) || old('role') === \App\Models\User::ROLE_ADMIN) selected @endif>Administrator</option>
                                    </select>
                                </div>

                                <div class="setting-form__item">
                                    <label>Подписка</label> <br>
                                    <input type="date"
                                           id="expired_at"
                                           name="expired_at"
                                           value="{{ old('expired_at', $user ? (new DateTimeImmutable($user->expired_at))->format('Y-m-d') : null) }}"
                                           class="form-control">
                                </div>
                            </div>
                        @endif

                        <button class="settings-form__btn btn btn2">
                            Сохранить
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
