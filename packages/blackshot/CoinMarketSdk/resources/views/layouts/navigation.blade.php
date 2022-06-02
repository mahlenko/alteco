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
                        @if (Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin())
                            <li class="{{ Request::routeIs('users.home') ? 'active' : null }}"><a href="{{ route('users.home') }}">Пользователи</a></li>
                            <li class="{{ Request::routeIs('settings.home') ? 'active' : null }}"><a href="{{ route('settings.home') }}">Настройки</a></li>
                        @endif
                        <li class="{{ Request::routeIs('users.edit') ? 'active' : null }}"><a href="{{ route('users.edit', \Illuminate\Support\Facades\Auth::id()) }}">Профиль</a></li>
                    </ul>
                @endauth
            </div>

            @auth
                {{-- tarif info --}}
{{--                <div class="nav__right d-flex">--}}
{{--                    <p class="header-pages__text">--}}
{{--                        У вас есть <span>3 дня</span>--}}
{{--                        <br>бесплатного доступа--}}
{{--                    </p>--}}
{{--                    <a href="#" class="nav__btn btn btn1">Улучшить тариф</a>--}}
{{--                </div>--}}

                {{-- Account --}}
                <div class="header-pages__info d-flex">
                    <img src="https://www.gravatar.com/avatar/{{ md5(\Illuminate\Support\Facades\Auth::user()->email.'?s=60&d=identicon') }}"
                         alt="" class="header-pages__ava">

                    <p class="header-pages__name">{{ \Illuminate\Support\Facades\Auth::user()->name }}</p>
    {{--                <a href="#" class="header-pages__btn">--}}
    {{--                    <img src="css/img/arr-icon.svg" alt="">--}}
    {{--                </a>--}}

                    <a href="{{ route('logout') }}" class="d-flex"
                       style="margin-left: 1rem;"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg xmlns="http://www.w3.org/2000/svg" style="height: 20px" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
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
{{--                    <div class="nav__right d-flex">--}}
{{--                        <p class="header-pages__text">--}}
{{--                            У вас есть <span> 3 дня</span> бесплатного доступа--}}
{{--                        </p>--}}
{{--                        <a href="#" class="nav__btn btn btn1">Улучшить тариф</a>--}}
{{--                    </div>--}}

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

