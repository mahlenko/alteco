<footer class="footer footer-pages" id="footer">
    <div class="container">
        <div class="footer__box">
            <div class="footer__flex d-flex">
                <a href="{{ route('home') }}" class="footer__logo">
                    <img src="{{ asset('css/img/footer/logo.svg') }}" alt="">
                </a>

                <div class="footer-pages__lists d-flex">
                    <ul class="nav__list">
                        <li>
                            <a href="/">
                                Главная
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('coins.home') ? 'active' : null }}">
                            <a href="{{ route('coins.home') }}">
                                Монеты
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('signals.home') ? 'active' : null }}">
                            <a href="{{ route('signals.home') }}">
                                Сигналы
                            </a>
                        </li>
                        @if (Auth::check() && \Illuminate\Support\Facades\Auth::user()->isAdmin())
                        <li class="{{ Request::routeIs('users.home') ? 'active' : null }}">
                            <a href="{{ route('users.home') }}">
                                Пользователи
                            </a>
                        </li>
                        @endif
                    </ul>
                    <ul class="nav__list">
                        @auth
                            <li class="{{ Request::routeIs('users.edit') ? 'active' : null }}">
                                <a href="{{ route('users.edit', \Illuminate\Support\Facades\Auth::id()) }}">
                                    Профиль
                                </a>
                            </li>

                            <li class="{{ Request::routeIs('subscribe') ? 'active' : null }}">
                                <a href="{{ route('subscribe') }}">
                                    @if (\Illuminate\Support\Facades\Auth::user()->tariff->isFree())
                                        Улучшить тариф
                                    @else
                                        Продлить подписку
                                    @endif
                                </a>
                            </li>
                        @else
                            <li class="{{ Request::routeIs('login') ? 'active' : null }}">
                                <a href="{{ route('login') }}">
                                    Войти
                                </a>
                            </li>
                            <li class="{{ Request::routeIs('register') ? 'active' : null }}">
                                <a href="{{ route('register') }}">
                                    Регистрация
                                </a>
                            </li>
                        @endauth
                    </ul>
                    <ul class="nav__list">
                        <li>
                            <a href="#">
                                Условия и услуги
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Политика конфиденциальности
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Помощь
                            </a>
                        </li>
                        <li>
                            <a href="/#ask">
                                FAQ
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="footer__socials d-flex">
                    <a href="#" target="blank">
                        <img src="{{ asset('css/img/footer/1.svg') }}" alt="" class="svg">
                    </a>
                    <a href="#" target="blank">
                        <img src="{{ asset('css/img/footer/2.svg') }}" alt="" class="svg">
                    </a>
                    <a href="#" target="blank">
                        <img src="{{ asset('css/img/footer/3.svg') }}" alt="" class="svg">
                    </a>
                    <a href="#" target="blank">
                        <img src="{{ asset('css/img/footer/4.svg') }}" alt="" class="svg">
                    </a>
                    <a href="#" target="blank">
                        <img src="{{ asset('css/img/footer/5.svg') }}" alt="" class="svg">
                    </a>
                </div>
            </div>
            <div class="footer__text">
                Simply Wall Street Pty Ltd (ACN 600 056 611) является корпоративным уполномоченным представителем
                (номер уполномоченного представителя: 467183) Sanlam Private Wealth Pty Ltd (AFSL № 337927). Любые
                советы, содержащиеся на этом веб-сайте, носят общий характер и были подготовлены без учета ваших
                целей, финансового положения или потребностей. Вы не должны полагаться на какие-либо советы и/или
                информацию, содержащуюся на этом веб-сайте, и перед принятием какого-либо инвестиционного решения мы
                рекомендуем вам подумать, подходит ли оно для вашей ситуации, и обратиться за соответствующей
                финансовой, налоговой и юридической консультацией. Пожалуйста, ознакомьтесь с нашим Руководством по
                финансовым услугам, прежде чем принимать решение о получении финансовых услуг от нас.
            </div>
        </div>
    </div>
</footer>
