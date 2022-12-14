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
                                Криптосканер
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
                                    @if (\Illuminate\Support\Facades\Auth::user()->tariff?->isFree())
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
                            <a href="{{ route('offer') }}">
                                Оферта
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
                    <a href="https://t.me/alteco_invest" target="blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 22">
                            <path d="M23.8312 0.645781L1.66875 9.19203C0.156246 9.79953 0.164997 10.6433 1.39125 11.0195L7.08125 12.7945L20.2462 4.48828C20.8687 4.10953 21.4375 4.31328 20.97 4.72828L10.3037 14.3545H10.3012L10.3037 14.3558L9.91125 20.2208C10.4862 20.2208 10.74 19.957 11.0625 19.6458L13.8262 16.9583L19.575 21.2045C20.635 21.7883 21.3962 21.4883 21.66 20.2233L25.4337 2.43828C25.82 0.889531 24.8425 0.188281 23.8312 0.645781V0.645781Z"></path>
                        </svg>
                    </a>

                    <a href="https://vk.com/alt_eco" target="blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg" viewBox="0 0 30 18">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M29.3125 1.435C29.52 0.7525 29.3125 0.25 28.3188 0.25H25.0375C24.2025 0.25 23.8175 0.68375 23.6087 1.1625C23.6087 1.1625 21.94 5.1575 19.5763 7.7525C18.8113 8.505 18.4638 8.74375 18.0463 8.74375C17.8375 8.74375 17.5238 8.505 17.5238 7.82125V1.435C17.5238 0.615 17.2938 0.25 16.5988 0.25H11.4388C10.9175 0.25 10.6037 0.63 10.6037 0.99125C10.6037 1.7675 11.7863 1.9475 11.9075 4.1325V8.88C11.9075 9.92125 11.7162 10.11 11.2987 10.11C10.1862 10.11 7.48 6.09625 5.87375 1.50375C5.5625 0.61 5.2475 0.25 4.40875 0.25H1.125C0.1875 0.25 0 0.68375 0 1.1625C0 2.015 1.1125 6.25 5.18125 11.8513C7.89375 15.6763 11.7125 17.75 15.1912 17.75C17.2775 17.75 17.535 17.29 17.535 16.4962V13.605C17.535 12.6838 17.7325 12.5 18.3938 12.5C18.8813 12.5 19.715 12.74 21.6625 14.5837C23.8875 16.77 24.2538 17.75 25.5063 17.75H28.7875C29.725 17.75 30.195 17.29 29.925 16.38C29.6275 15.475 28.565 14.1612 27.1563 12.6025C26.3913 11.715 25.2438 10.7588 24.895 10.28C24.4088 9.66625 24.5475 9.3925 24.895 8.84625C24.895 8.84625 28.895 3.31375 29.3112 1.435H29.3125Z"></path>
                        </svg>
                    </a>

                    <a href="https://www.youtube.com/channel/UCRfWYN5BQJmQp7b9dPcWnWw/featured" target="blank">
                        <svg xmlns="http://www.w3.org/2000/svg" class="svg" viewBox="0 0 576 512" style="height: 1.65rem">
                            <path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/>
                        </svg>
                    </a>
                </div>
            </div>
{{--            <div class="footer__text">--}}
{{--                Simply Wall Street Pty Ltd (ACN 600 056 611) является корпоративным уполномоченным представителем--}}
{{--                (номер уполномоченного представителя: 467183) Sanlam Private Wealth Pty Ltd (AFSL № 337927). Любые--}}
{{--                советы, содержащиеся на этом веб-сайте, носят общий характер и были подготовлены без учета ваших--}}
{{--                целей, финансового положения или потребностей. Вы не должны полагаться на какие-либо советы и/или--}}
{{--                информацию, содержащуюся на этом веб-сайте, и перед принятием какого-либо инвестиционного решения мы--}}
{{--                рекомендуем вам подумать, подходит ли оно для вашей ситуации, и обратиться за соответствующей--}}
{{--                финансовой, налоговой и юридической консультацией. Пожалуйста, ознакомьтесь с нашим Руководством по--}}
{{--                финансовым услугам, прежде чем принимать решение о получении финансовых услуг от нас.--}}
{{--            </div>--}}
        </div>
    </div>
</footer>
