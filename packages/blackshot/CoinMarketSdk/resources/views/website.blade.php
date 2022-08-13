<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="date=no">
    <meta name="format-detection" content="address=no">
    <meta name="format-detection" content="email=no">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>AltEco</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <meta property="og:locale" content="ru-Ru">
    <meta property="og:type" content="website">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&family=Merriweather:ital,wght@1,300;1,700;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="css/jquery.arcticmodal-0.3.css">
    <link rel="stylesheet" type="text/css" href="css/landing/main.css">

</head>

<body>
<div class="body-wrap">
    <header class="header" id="header">
        <img src="css/img/header.png" alt="" class="head-pic">
        <nav>
            <div class="container">
                <div class="nav__box d-flex">
                    <a href="/" class="nav__logo">
                        <img src="css/img/logo.svg" alt="">
                    </a>
                    <ul class="nav__list d-flex">
                        <li>
                            <a href="#about" class="go_to">
                                О нас
                            </a>
                        </li>
                        <li>
                            <a href="#van" class="go_to">
                                Преимущества
                            </a>
                        </li>
                        <li>
                            <a href="#our" class="go_to">
                                Тарифы
                            </a>
                        </li>
                        <li>
                            <a href="#pay" class="go_to">
                                Как оплатить
                            </a>
                        </li>
                        <li>
                            <a href="#" class="go_to">
                                Авторы
                            </a>
                        </li>
                        <li>
                            <a href="#rev" class="go_to">
                                Отзывы
                            </a>
                        </li>
                        <li>
                            <a href="#ask" class="go_to">
                                FAQ
                            </a>
                        </li>
                    </ul>
                    <div class="nav__right d-flex">
                        @auth
                            <a href="{{ route('login') }}" class="nav__link" style="margin: 20px;"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Выйти
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                            <a href="{{ route('coins.home') }}" class="nav__btn btn btn1">
                                Личный кабинет
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav__link calc" style="margin: 20px;">
                                Войти
                            </a>

                            <a href="{{ route('register') }}" class="nav__btn btn btn1">
                                Бесплатный доступ
                            </a>
                        @endif
                    </div>
                    <div class="menu">
                        <a href="#" class="button"><span></span></a>
                    </div>
                    <div class="burger">
                        <div class="nav__right d-flex">
                            <a href="{{ route('login') }}" class="nav__link">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="nav__btn btn btn1">
                                Register for Free Plan
                            </a>
                        </div>
                        <ul class="nav__list">
                            <li>
                                <a href="#">
                                    О нас
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Преимущества
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Тарифы
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Как оплатить
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Авторы
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    Отзывы
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    FAQ
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <div class="header__box d-flex">
                <div class="header__content">
                    <h1>
                        <span>Криптосканер:</span> сервис, который позволяет в 2 клика отбирать перспективные криптопроекты
                    </h1>
                    <p class="header__text">
                        Вам не нужно тратить время на анализ крипторынка и вручную отбирать прибыльные криптовалюты среди
                        сотни вариантов. С Криптосканером вы сможете сразу брать, применять и получать результат.
                    </p>
                    <div class="header__links d-flex">
                        <a href="#" class="header__btn1 btn btn2">
                            Узнать подробнее
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <section class="about" id="about">
        <div class="container">
            <div class="block">
                <h2>
                    О нашем <span>сервисе</span>
                </h2>
                <p class="text">
                    Amet, consequat velit fermentum lacinia nullam sodales ante.
                    Tincidunt nunc malesuada pellentesque lorem
                </p>
                <span class="num">
						01
					</span>
            </div>
            <div class="about__box d-flex">
                <div class="about__content">
                    <p class="about__text">
                        Криптосканер - это авторская разработка онлайн-школы инвестиций Alteco и её основателей Александра
                        и Ксении Кожевниковых.
                    </p>
                    <p class="about__text">
                        В основе сервиса лежит метод Кожевникова, который позволяет за 2 простых шага отбирать
                        криптопроекты, которые растут даже на падающем рынке:
                    </p>
                    <p class="about__text">
                        Первый шаг - инвестировать только в те проекты, в которые инвестируют фонды. <br>
                        Второй шаг - покупать криптовалюты, когда они идут вверх по рейтингу.
                    </p>
                    <p class="about__text">
                        Вручную анализировать криптопроекты трудозатратно. Поэтому нам пришла идея создать удобный и
                        понятный сервис, которым смогут пользоваться обычные люди, чтобы создать свой капитал на
                        криптовалютах.
                    </p>
                    <p class="about__text">
                        Криптосканер собирает все криптопроекты, в которые инвестируют фонды, анализирует передвижение их в
                        рейтинге, автоматизирует контроль и их отбор.
                    </p>
                    <p class="about__text">
                        Вы получите всю необходимую информацию о криптовалютах. Для этого вам нужно просто задать нужный
                        фильтр в категории. Система подберет вам все проекты, которые входят в портфель инвестицонных
                        фондов и проранжирует их от самого крупного до минимального. Так вы поймете, пользуется ли проект
                        популярностью среди инвесторов и вкладывают ли в него фонды.
                    </p>
                    <p class="about__text">
                        При помощи Криптосканера вы сможете отбирать в свой портфель монеты, которые стоят даже меньше
                        доллара.
                    </p>
                </div>
                <img src="css/img/about.png?v=2" alt="" class="about__pic">
            </div>
            <div class="about__wrap">
                <table class="profile__table adaptive-table">
                    <thead>
                    <tr class="profile__row">
                        <td class="active">#</td>
                        <td class="active pad">Name / Last percentage change <a href="#"><img
                                    src="css/img/table/arr1.svg" alt="" class="about__arr svg"></a> </td>
                        <td class="active">Price</td>
                        <td class="active">Rank <a href="#"><img src="css/img/table/arr1.svg" alt=""
                                                                 class="about__arr svg"></a></td>
                        <td class="active">Rank (2d selected in the filter) <a href="#"><img
                                    src="css/img/table/arr1.svg" alt="" class="about__arr svg"></a></td>
                        <td class="active">Rank (30D) <a href="#"><img src="css/img/table/arr1.svg" alt=""
                                                                       class="about__arr svg"></a></td>
                        <td class="active">Rank (60D) <a href="#"><img src="css/img/table/arr1.svg" alt=""
                                                                       class="about__arr svg"></a></td>
                        <td class="active">Last updated</td>
                        <td class="active"></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="profile__row">
                        <td class="active" data-label="#">1</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="css/img/table/logo1.png" alt="" class="table__logo">
                                    <p class="table__text">
                                        <span>Bitcoin</span> BTC
                                    </p>
                                </div>
                                <div class="table__flex d-flex">
                                    <p class="table__num">
                                        0.48
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="active" data-label="Price">$32,950.87</td>
                        <td class="active" data-label="Rank">1</td>
                        <td class="active" data-label="Rank (2d selected in the filter)">———</td>
                        <td class="active" data-label="Rank (30D)">———</td>
                        <td class="active" data-label="Rank (60D)">———</td>
                        <td class="active" data-label="Last updated">3 hours ago</td>
                        <td class="active" data-label="">
                            <div class="table__icons d-flex">
                                <a href="#" class="table__star">
                                    <img src="css/img/table/star.svg" alt="" class="svg">
                                </a>
                                <a href="#" class="table__icon">
                                    <img src="css/img/table/icon.svg" alt="" class="svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">2</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="css/img/table/logo2.png" alt="" class="table__logo">
                                    <p class="table__text">
                                        <span>Ethereum</span> ETH
                                    </p>
                                </div>
                                <div class="table__flex d-flex">
                                    <p class="table__num">
                                        0.07
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="active" data-label="Price">$2,401.71</td>
                        <td class="active" data-label="Rank">2</td>
                        <td class="active" data-label="Rank (2d selected in the filter)">———</td>
                        <td class="active" data-label="Rank (30D)">———</td>
                        <td class="active" data-label="Rank (60D)">———</td>
                        <td class="active" data-label="Last updated">3 hours ago</td>
                        <td class="active" data-label="">
                            <div class="table__icons d-flex">
                                <a href="#" class="table__star able">
                                    <img src="css/img/table/star.svg" alt="" class="svg">
                                </a>
                                <a href="#" class="table__icon able">
                                    <img src="css/img/table/icon.svg" alt="" class="svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">3</td>
                        <td class="active green" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="css/img/table/logo3.png" alt="" class="table__logo">
                                    <p class="table__text">
                                        <span>Tether</span> UDST
                                    </p>
                                </div>
                                <div class="table__flex d-flex">
                                    <p class="table__num">
                                        0.01
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="active" data-label="Price">$1.00</td>
                        <td class="active" data-label="Rank">3</td>
                        <td class="active" data-label="Rank (2d selected in the filter)">———</td>
                        <td class="active" data-label="Rank (30D)">———</td>
                        <td class="active" data-label="Rank (60D)">———</td>
                        <td class="active" data-label="Last updated">3 hours ago</td>
                        <td class="active" data-label="">
                            <div class="table__icons d-flex">
                                <a href="#" class="table__star">
                                    <img src="css/img/table/star.svg" alt="" class="svg">
                                </a>
                                <a href="#" class="table__icon">
                                    <img src="css/img/table/icon.svg" alt="" class="svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">4</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="css/img/table/logo4.png" alt="" class="table__logo">
                                    <p class="table__text">
                                        <span>Binance Coin</span> BNB
                                    </p>
                                </div>
                                <div class="table__flex d-flex">
                                    <p class="table__num">
                                        0.49
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="active" data-label="Price">$331.237</td>
                        <td class="active" data-label="Rank">4</td>
                        <td class="active" data-label="Rank (2d selected in the filter)">———</td>
                        <td class="active" data-label="Rank (30D)">———</td>
                        <td class="active" data-label="Rank (60D)">———</td>
                        <td class="active" data-label="Last updated">3 hours ago</td>
                        <td class="active" data-label="">
                            <div class="table__icons d-flex">
                                <a href="#" class="table__star">
                                    <img src="css/img/table/star.svg" alt="" class="svg">
                                </a>
                                <a href="#" class="table__icon">
                                    <img src="css/img/table/icon.svg" alt="" class="svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">5</td>
                        <td class="active green" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="css/img/table/logo5.png" alt="" class="table__logo">
                                    <p class="table__text">
                                        <span>USD Coin</span> USDC
                                    </p>
                                </div>
                                <div class="table__flex d-flex">
                                    <p class="table__num">
                                        0.02
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="active" data-label="Price">$1.00</td>
                        <td class="active" data-label="Rank">5</td>
                        <td class="active" data-label="Rank (2d selected in the filter)">———</td>
                        <td class="active" data-label="Rank (30D)">———</td>
                        <td class="active" data-label="Rank (60D)">———</td>
                        <td class="active" data-label="Last updated">3 hours ago</td>
                        <td class="active" data-label="">
                            <div class="table__icons d-flex">
                                <a href="#" class="table__star">
                                    <img src="css/img/table/star.svg" alt="" class="svg">
                                </a>
                                <a href="#" class="table__icon">
                                    <img src="css/img/table/icon.svg" alt="" class="svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">6</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="css/img/table/logo6.png" alt="" class="table__logo">
                                    <p class="table__text">
                                        <span>XRP</span> XRP
                                    </p>
                                </div>
                                <div class="table__flex d-flex">
                                    <p class="table__num">
                                        0.48
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="active" data-label="Price">$32,950.87</td>
                        <td class="active" data-label="Rank">6</td>
                        <td class="active" data-label="Rank (2d selected in the filter)">———</td>
                        <td class="active" data-label="Rank (30D)">———</td>
                        <td class="active" data-label="Rank (60D)">———</td>
                        <td class="active" data-label="Last updated">3 hours ago</td>
                        <td class="active" data-label="">
                            <div class="table__icons d-flex">
                                <a href="#" class="table__star able">
                                    <img src="css/img/table/star.svg" alt="" class="svg">
                                </a>
                                <a href="#" class="table__icon able">
                                    <img src="css/img/table/icon.svg" alt="" class="svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">7</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="css/img/table/logo7.png" alt="" class="table__logo">
                                    <p class="table__text">
                                        <span>SolanaL</span> SOL
                                    </p>
                                </div>
                                <div class="table__flex d-flex">
                                    <p class="table__num">
                                        0.48
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="active" data-label="Price">$71.65</td>
                        <td class="active" data-label="Rank">7</td>
                        <td class="active" data-label="Rank (2d selected in the filter)">———</td>
                        <td class="active" data-label="Rank (30D)">———</td>
                        <td class="active" data-label="Rank (60D)">———</td>
                        <td class="active" data-label="Last updated">3 hours ago</td>
                        <td class="active" data-label="">
                            <div class="table__icons d-flex">
                                <a href="#" class="table__star able">
                                    <img src="css/img/table/star.svg" alt="" class="svg">
                                </a>
                                <a href="#" class="table__icon able">
                                    <img src="css/img/table/icon.svg" alt="" class="svg">
                                </a>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section class="van" id="van">
        <img src="css/img/top.png" alt="" class="van__fig">
        <div class="container">
            <div class="block">
                <h2>
                    Наши <span>преимущества</span>
                </h2>
                <p class="text">
                    Amet, consequat velit fermentum lacinia nullam sodales ante.
                    Tincidunt nunc malesuada pellentesque lorem
                </p>
                <span class="num">
						02
					</span>
            </div>
            <div class="van__box d-flex">
                <div class="van__item">
                    <div class="van__block">
                        <img src="css/img/icon3.svg" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Интуитивно понятный интерфейс
                    </p>
                    <!-- <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p> -->
                </div>
                <div class="van__item">
                    <div class="van__block">
                        <img src="css/img/icon1.svg" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Всегда актуальная информация
                    </p>
                    <!-- <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p> -->
                </div>
                <div class="van__item">
                    <div class="van__block">
                        <img src="css/img/icon5.svg" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Авторская разработка, которой нет аналогов
                    </p>
                    <!-- <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p> -->
                </div>
            </div>
        </div>
    </section>
    <section class="our" id="our">
        <div class="container">
            <div class="block">
                <h2>
                    Наши <span>тарифы</span>
                </h2>
                <p class="text">
                    Amet, consequat velit fermentum lacinia nullam sodales ante.
                    Tincidunt nunc malesuada pellentesque lorem
                </p>
                <span class="num">
						03
					</span>
            </div>

            <div class="our__box d-flex" style="flex-wrap: wrap; row-gap: 1rem">
                @foreach($tariffs as $tariff)
                    <div class="our__item">
                        <p class="our__time">{{ $tariff->name }}</p>
                        <p class="our__sum">{{ $tariff->amount }} руб.</p>
                        <p class="our__res">{!! $tariff->description !!}</p>

                        <a href="#" class="our__btn btn btn2">Стать резидентом</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="about pay" id="pay">
        <div class="container">
            <div class="block">
                <h2>
                    Как <span>оплатить</span>
                </h2>
                <p class="text">
                    Amet, consequat velit fermentum lacinia nullam sodales ante.
                    Tincidunt nunc malesuada pellentesque lorem
                </p>
                <span class="num">
						04
					</span>
            </div>
            <div class="about__box d-flex">
                <div class="about__content">
                    <p class="about__text">
                        Получить доступ к Криптосканеру могут только резиденты курса Криптоинвестор
                    </p>
                    <p class="about__text">
                        Чтобы оплатить, выберите нужный вам тариф, нажмите кнопку “Выбираю”, заполните свое имя, почту и
                        номер телефона. После этого оплатить заказ.
                    </p>
                </div>
                <img src="css/img/about2.png?v=2" alt="" class="about__pic">
            </div>
        </div>
    </section>
    <section class="authors" id="authors">
        <div class="container">
            <div class="block">
                <h2>
                    Наши <span> авторы</span>
                </h2>
                <p class="text">
                    Amet, consequat velit fermentum lacinia nullam sodales ante.
                    Tincidunt nunc malesuada pellentesque lorem
                </p>
                <span class="num">
						05
					</span>
            </div>
            <div class="authors__box d-flex">
                <img src="css/img/new1.png" alt="" class="authors__pic">
                <div class="authors__content">
                    <p class="authors__name">
                        Александр и Ксения <br> Кожевниковы
                    </p>
                    <p class="authors__text">
                        Основатели онлайн-школы инвестиций ALTECO с государственной лицензией на образовательную
                        деятельность. Авторы курсов «Конструктор современных инвестиций» и «Криптоинвестор»
                    </p>
                    <p class="authors__text">
                        "Я начинал инвестировать в цифровые активы в 2017 на падающем рынке и мне нужно было находить
                        активы, которые растут. Я анализировал много инвестиционных фондов, выявлял закономерности роста
                        цифровых валют и выработал собственную методологию, которая позволяет мне сейчас находить «золотые»
                        активы и получать колоссальную доходность."
                    </p>
                    <p class="authors__text">
                        Александр Кожевников
                    </p>
                    <div class="authors__flex d-flex">
                        <div class="authors__item d-flex">
                            <img src="css/img/new-icon1.svg" alt="" class="authors__icon">
                            <p>
                                20 лет опыта <br> в инвестициях
                            </p>
                        </div>
                        <div class="authors__item d-flex">
                            <img src="css/img/new-icon2.svg" alt="" class="authors__icon">
                            <p>
                                40000 учеников <br> в России и за рубежом
                            </p>
                        </div>
                        <div class="authors__item d-flex">
                            <img src="css/img/new-icon3.svg" alt="" class="authors__icon">
                            <p>
                                Собственная методика отбора цифровых активов, которые растут даже на падающем рынке
                            </p>
                        </div>
                        <div class="authors__item d-flex">
                            <img src="css/img/new-icon4.svg" alt="" class="authors__icon">
                            <p>
                                25 выступлений на <br> конференциях <br> по инвестированию
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="authors__wrap d-flex">
                <div class="authors__info">
                    <p class="authors__main">
                        Получили премию
                    </p>
                    <p class="authors__el">
                        «INVESTMENT LEADERS AWARD – 21»
                        в номинации Лучший онлайн-курс по инвестициям в криптовалюту.
                    </p>
                </div>
                <img src="css/img/new2.png" alt="" class="authors__img">
            </div>
        </div>
    </section>
    <section class="rev" id="rev">
        <div class="container">
            <div class="block">
                <h2>
                    <span>Отзывы</span> наших клиентов
                </h2>
                <p class="text">
                    Amet, consequat velit fermentum lacinia nullam sodales ante.
                    Tincidunt nunc malesuada pellentesque lorem
                </p>
                <span class="num">
						06
					</span>
            </div>
            <div class="rev__box d-flex">
                <div class="rev__item">
                    <div class="rev__top d-flex">
                        <img src="css/img/reviews/1.png" alt="" class="rev__ava">
                        <div class="rev__content">
                            <p class="rev__name">
                                Gerardo Maio
                            </p>
                            <p class="rev__job">
                                Tomahawk Digital Marketing
                            </p>
                        </div>
                    </div>
                    <p class="rev__text">
                        Euismod scelerisque leo tellus tellus. Mi, varius non mattis enim mauris. Enim adipiscing tellus
                        est facilisis tristique. Felis lacus sit nec neque sed risus. Massa ac donec tellus ut. At a
                        faucibus vivamus est. Nibh risus mauris, dignissim enim sit ante ac ultrices.
                    </p>
                </div>
                <div class="rev__item">
                    <div class="rev__top d-flex">
                        <img src="css/img/reviews/2.png" alt="" class="rev__ava">
                        <div class="rev__content">
                            <p class="rev__name">
                                Davis Harris
                            </p>
                            <p class="rev__job">
                                SEO specialist for SMEs
                            </p>
                        </div>
                    </div>
                    <p class="rev__text">
                        Vulputate eu at euismod donec. Consequat adipiscing facilisis vel sem. Leo massa id proin volutpat
                        velit ac auctor malesuada. Etiam blandit eget erat turpis etiam at et vitae. Adipiscing in sagittis
                        fermentum aliquam lobortis donec vitae. Egestas laoreet pharetra, fames venenatis, arcu in nisl
                        quis. Mollis pharetra, rhoncus at aliquam, urna.
                    </p>
                </div>
                <div class="rev__item">
                    <div class="rev__top d-flex">
                        <img src="css/img/reviews/3.png" alt="" class="rev__ava">
                        <div class="rev__content">
                            <p class="rev__name">
                                Jeroen
                            </p>
                            <p class="rev__job">
                                Online Marketeer (SEO)
                            </p>
                        </div>
                    </div>
                    <p class="rev__text">
                        Euismod scelerisque leo tellus tellus. Mi, varius non mattis enim mauris. Enim adipiscing tellus
                        est facilisis tristique. Felis lacus sit nec neque sed risus. Massa ac donec tellus ut. At a
                        faucibus vivamus est. Nibh risus mauris, dignissim enim sit ante ac ultrices.
                    </p>
                </div>
                <div class="rev__item">
                    <div class="rev__top d-flex">
                        <img src="css/img/reviews/4.png" alt="" class="rev__ava">
                        <div class="rev__content">
                            <p class="rev__name">
                                Chrystel Stevens
                            </p>
                            <p class="rev__job">
                                Tomahawk Digital Marketing
                            </p>
                        </div>
                    </div>
                    <p class="rev__text">
                        Euismod scelerisque leo tellus tellus. Mi, varius non mattis enim mauris. Enim adipiscing tellus
                        est facilisis tristique. Felis lacus sit nec neque sed risus. Massa ac donec tellus ut. At a
                        faucibus vivamus est. Nibh risus mauris, dignissim enim sit ante ac ultrices.
                    </p>
                </div>
                <div class="rev__item">
                    <div class="rev__top d-flex">
                        <img src="css/img/reviews/5.png" alt="" class="rev__ava">
                        <div class="rev__content">
                            <p class="rev__name">
                                Gerardo Maio
                            </p>
                            <p class="rev__job">
                                In between internet & marketing
                            </p>
                        </div>
                    </div>
                    <p class="rev__text">
                        Euismod scelerisque leo tellus tellus. Mi, varius non mattis enim mauris. Enim adipiscing tellus
                        est facilisis tristique. Felis lacus sit nec neque sed risus. Massa ac donec tellus ut. At a
                        faucibus vivamus est. Nibh risus mauris, dignissim enim sit ante ac ultrices.
                    </p>
                </div>
                <div class="rev__item">
                    <div class="rev__top d-flex">
                        <img src="css/img/reviews/6.png" alt="" class="rev__ava">
                        <div class="rev__content">
                            <p class="rev__name">
                                Gerardo Maio
                            </p>
                            <p class="rev__job">
                                Tomahawk Digital Marketing
                            </p>
                        </div>
                    </div>
                    <p class="rev__text">
                        Euismod scelerisque leo tellus tellus. Mi, varius non mattis enim mauris. Enim adipiscing tellus
                        est facilisis tristique. Felis lacus sit nec neque sed risus. Massa ac donec tellus ut. At a
                        faucibus vivamus est. Nibh risus mauris, dignissim enim sit ante ac ultrices.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <section class="ask" id="ask">
        <div class="container">
            <div class="block">
                <h2>
                    <span>Часто</span> задаваемые вопросы
                </h2>
                <p class="text">
                    Amet, consequat velit fermentum lacinia nullam sodales ante.
                    Tincidunt nunc malesuada pellentesque lorem
                </p>
                <span class="num">
						07
					</span>
            </div>
            <div class="accordion-container">
                <div class="set">
                    <a href="#">
                        <p>
                            Криптосканер вы сами составляете или пользуетесь некими ресурсами?
                        </p>
                    </a>
                    <div class="content">
                        <p>
                            Криптосканер - это наша собственная платформа отбора криптопроектов. Мы разработывали её 2 года,
                            вложили много сил и энергии. Над криптосканером работает большая команда программистов, которая
                            каждый день совершенствует сервис и улучшает его работу.
                        </p>
                    </div>
                </div>
                <div class="set">
                    <a href="#">
                        <p>
                            Откуда подтягивается информация в Криптосканер?
                        </p>
                    </a>
                    <div class="content">
                        <p>
                            Amet interdum duis sit orci. Ac quisque sit integer scelerisque. Quisque tristique urna massa
                            dui dui habitasse. Placerat nisl et et vestibulum. Et sagittis elementum lacinia amet velit
                            bibendum id. Velit, tincidunt sagittis, lacus, tortor, adipiscing viverra vulputate ac. Quis cum
                            mauris cras massa, felis at id.
                            Id neque, facilisis sollicitudin amet. Lorem sodales eu adipiscing elementum maecenas id amet
                            lacus, vel. Dolor, viverra augue pellentesque neque vitae porttitor elit nulla.
                        </p>
                    </div>
                </div>
                <div class="set">
                    <a href="#">
                        <p>
                            Можно ли отдельно купить Криптосканер?
                        </p>
                    </a>
                    <div class="content">
                        <p>
                            Amet interdum duis sit orci. Ac quisque sit integer scelerisque. Quisque tristique urna massa
                            dui dui habitasse. Placerat nisl et et vestibulum. Et sagittis elementum lacinia amet velit
                            bibendum id. Velit, tincidunt sagittis, lacus, tortor, adipiscing viverra vulputate ac. Quis cum
                            mauris cras massa, felis at id.
                            Id neque, facilisis sollicitudin amet. Lorem sodales eu adipiscing elementum maecenas id amet
                            lacus, vel. Dolor, viverra augue pellentesque neque vitae porttitor elit nulla.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="footer" id="footer">
        <!-- <img src="css/img/footer/footer.png" alt="" class="footer__bg"> -->
        <div class="container">
            <div class="footer__box">
                <div class="footer__flex d-flex">
                    <a href="#" class="footer__logo">
                        <img src="css/img/footer/logo.svg" alt="">
                    </a>
                    <ul class="nav__list d-flex">
                        <li>
                            <a href="#">
                                О нас
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Преимущества
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Тарифы
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Как оплатить
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Авторы
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Отзывы
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                FAQ
                            </a>
                        </li>
                    </ul>
                    <div class="footer__socials d-flex">
                        <a href="#" target="blank">
                            <img src="css/img/footer/1.svg" alt="" class="svg">
                        </a>
                        <a href="#" target="blank">
                            <img src="css/img/footer/2.svg" alt="" class="svg">
                        </a>
                        <a href="#" target="blank">
                            <img src="css/img/footer/3.svg" alt="" class="svg">
                        </a>
                        <a href="#" target="blank">
                            <img src="css/img/footer/4.svg" alt="" class="svg">
                        </a>
                        <a href="#" target="blank">
                            <img src="css/img/footer/5.svg" alt="" class="svg">
                        </a>
                    </div>
                </div>
                <div class="footer__text">
                    Simply Wall Street Pty Ltd (ACN 600 056 611), is a Corporate Authorised Representative (Authorised
                    Representative Number: 467183) of Sanlam Private Wealth Pty Ltd (AFSL No. 337927). Any advice
                    contained in this website is general advice only and has been prepared without considering your
                    objectives, financial situation or needs. You should not rely on any advice and/or information
                    contained in this website and before making any investment decision we recommend that you consider
                    whether it is appropriate for your situation and seek appropriate financial, taxation and legal
                    advice. Please read our Financial Services Guide before deciding whether to obtain financial services
                    from us.
                </div>
            </div>
        </div>
    </footer>

    {{-- login --}}
    <div style="display:none;">
        <div class="box-modal pop1" id="pop6">
            <div class="popup">
                <div class="popup-call__box">
                    <div class="box-modal__close articmodal-close">
                        <img src="css/img/close.svg" alt="" class="svg">
                    </div>
                    <div class="popup__flex d-flex">
                        <div class="popup__left">
                            <img src="css/img/logo.svg" alt="" class="popup__logo">
                            <img src="css/img/popup.png" alt="" class="popup__pic">
                        </div>
                        <div class="popup__right">
                            <p class="popup__title">
                                Войти
                            </p>
                            <form action="{{ route('login') }}" method="post" class="settings-form">
                                @csrf
                                <div class="settings__el">
                                    <div class="setting-form__item">
                                        <label>Email:</label> <br>
                                        <input type="email" name="email" placeholder="john@gmail.com" autocomplete="email" class="name">
                                    </div>
                                    <div class="setting-form__item">
                                        <label>Пароль:</label> <br>
                                        <input type="password" name="password" placeholder="****************" class="pass">
                                    </div>
                                </div>
                                <button class="settings-form__btn btn btn2">
                                    Вход
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//yandex.st/jquery/1.9.1/jquery.min.js"></script>
<script src="js/landing.js"></script>
</body>

</html>
