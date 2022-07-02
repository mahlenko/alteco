<nav>
    <div class="container">
        <div class="nav__box d-flex">
            <div style="display: flex; align-items: center; column-gap: 3rem;">
                {{-- logotype --}}
                <a href="{{ route('home') }}" class="nav__logo">
                    <img src="{{ asset('css/img/logo2.svg') }}" alt="">
                </a>

                {{-- Desktop navigation --}}
                @auth
                    <ul class="nav__list d-flex" style="max-width: none;">
                        <li class="{{ Request::routeIs('coins.home') ? 'active' : null }}"><a href="{{ route('coins.home') }}">Монеты</a></li>
                        <li class="{{ Request::routeIs('signals.home') ? 'active' : null }}"><a href="{{ route('signals.home') }}">Сигналы</a></li>
                    </ul>
                @endauth
            </div>

            @auth
                {{-- Account --}}
                <div class="header-pages__info d-flex">
                    @if (!\Illuminate\Support\Facades\Auth::user()->isAdmin())
                        @if (\Illuminate\Support\Facades\Auth::user()->expiredDays() <= 30)
                            <a href="{{ route('subscribe') }}" class="nav__btn btn btn1">
                                @if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())
                                    Улучшить тариф
                                @else
                                    Продлить подписку
                                @endif
                            </a>
                        @endif
                    @endif

                    {{-- Admin dropdown --}}
                    @if (Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin())
                        <div class="dropdown">
                            <a href="#" class="setting-btn" data-bs-toggle="dropdown" style="column-gap: .2rem">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <span>Администрирование</span>
                            </a>

                            <ul class="dropdown-menu">
                                <li class="dropdown-item {{ Request::routeIs('tariffs.home') ? 'active' : null }}">
                                    <a href="{{ route('tariffs.home') }}">Тарифы</a>
                                </li>

                                <li class="dropdown-item {{ Request::routeIs('banners.home') ? 'active' : null }}">
                                    <a href="{{ route('banners.home') }}">Баннеры</a>
                                </li>

                                <li class="dropdown-item {{ Request::routeIs('users.home') ? 'active' : null }}">
                                    <a href="{{ route('users.home') }}">Пользователи</a>
                                </li>

                                <li class="dropdown-item {{ Request::routeIs('settings.home') ? 'active' : null }}">
                                    <a href="{{ route('settings.home') }}">API Coinmarket</a>
                                </li>
                            </ul>
                        </div>
                    @endif

                    <img src="https://www.gravatar.com/avatar/{{ md5(\Illuminate\Support\Facades\Auth::user()->email.'?s=60&d=identicon') }}"
                         alt="" class="header-pages__ava">

                    <div>
                        {{-- dropdown --}}
                        <div class="dropdown">
                            <a href="#" class="d-flex" data-bs-toggle="dropdown">
                                <span class="header-pages__name">{{ \Illuminate\Support\Facades\Auth::user()->name }}</span>
                                <img src="{{ asset('css/img/arr-icon.svg') }}" alt="">
                            </a>

                            <ul class="dropdown-menu">
                                <li class="dropdown-item {{ Request::routeIs('users.edit') ? 'active' : null }}">
                                    <a href="{{ route('users.edit', \Illuminate\Support\Facades\Auth::id()) }}">Мой профиль</a>
                                </li>

                                <li class="dropdown-item {{ Request::routeIs('subscribe') ? 'active' : null }}">
                                    <a href="{{ route('subscribe') }}">
                                        @if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())
                                            Улучшить тариф
                                        @else
                                            Продлить подписку
                                        @endif
                                    </a>
                                </li>

                                <li class="separator"></li>

                                <li class="dropdown-item {{ Request::routeIs('users.home') ? 'active' : null }}">
                                    <a href="{{ route('logout') }}" class="d-flex"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 20px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span style="padding-left: .25rem;">Выход</span>
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                        {{--  --}}
                        @auth
                            @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                                <small class="header-pages__text">
                                    <span>Администратор</span>
                                </small>
                            @else
                                <small class="header-pages__text">
                                    Подписка действует еще <span>{{ Auth::user()->expiredAtText() }}</span>
                                </small>
                            @endif
                        @endauth
                    </div>
                </div>
            @else
                <div class="d-flex" style="column-gap: 1rem;">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="header__link">
                            {{ __('Войти') }}
                        </a>
                    @endif

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="header__link">
                            {{ __('Регистрация') }}
                        </a>
                    @endif
                </div>
            @endauth

            @auth
                <div class="menu">
                    <a href="#" class="button"><span></span></a>
                </div>
                <div class="burger">
                    <div class="nav__right d-flex">
                        <p class="header-pages__text">
                            У вас <span>{{ Auth::user()->expiredAtText() }}</span>

                            @if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())
                                <br>бесплатного доступа
                            @endif
                        </p>
                        @if (!\Illuminate\Support\Facades\Auth::user()->isAdmin())
                            <a href="{{ route('subscribe') }}" class="nav__btn btn btn1">Улучшить тариф</a>
                        @endif
                    </div>

                    <div class="header-pages__info d-flex">
                        <img src="https://www.gravatar.com/avatar/{{ md5(\Illuminate\Support\Facades\Auth::user()->email.'?s=60&d=identicon') }}"
                             alt="" class="header-pages__ava">

                        <p class="header-pages__name">{{ \Illuminate\Support\Facades\Auth::user()->name }}</p>

{{--                        <a href="#" class="header-pages__btn">--}}
{{--                            <img src="css/img/arr-icon.svg" alt="">--}}
{{--                        </a>--}}
                        <a href="{{ route('logout') }}" class="header__link"
                           style="margin-left: 1rem;"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 20px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    </div>

                    <ul class="nav__list">
                        <li class="{{ Request::routeIs('coins.home') ? 'active' : null }}"><a href="{{ route('coins.home') }}">Монеты</a></li>
                        <li class="{{ Request::routeIs('signals.home') ? 'active' : null }}"><a href="{{ route('signals.home') }}">Сигналы</a></li>
                        @if (Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin())
                            <li class="{{ Request::routeIs('users.home') ? 'active' : null }}"><a href="{{ route('users.home') }}">Пользователи</a></li>
                            <li class="{{ Request::routeIs('settings.home') ? 'active' : null }}"><a href="{{ route('settings.home') }}">Настройки</a></li>
                        @else
                            <li class="{{ Request::routeIs('users.edit') ? 'active' : null }}"><a href="{{ route('users.edit', \Illuminate\Support\Facades\Auth::id()) }}">Настройки</a></li>
                        @endif
                    </ul>
                </div>
            @endif
        </div>
    </div>
</nav>

