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

    <link rel="stylesheet" href="{{ asset('css/jquery.arcticmodal-0.3.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('css/main.css') }}">

</head>

<body>
<div class="body-wrap">
    <header class="header" id="header">
        <img src="{{ asset('images/header.png') }}" alt="" class="head-pic">
        <nav>
            <div class="container">
                <div class="nav__box d-flex">
                    <a href="#" class="nav__logo">
                        <img src="{{ asset('css/img/logo.svg') }}" alt="">
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
                        <a href="{{ 'login' }}" class="nav__link">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="nav__btn btn btn1">
                            Register for Free Plan
                        </a>
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
                        <span>Est praesent</span> eu sit cursus vitae eget dictumst tortor egera.
                    </h1>
                    <p class="header__text">
                        Amet, consequat velit fermentum lacinia nullam sodales ante. Tincidunt nunc malesuada pellentesque
                        lorem dignissim dolor sem egestas. Diam placerat nunc at id. Nam ipsum sed elementum ipsum est,
                        felis.
                    </p>
                    <div class="header__links d-flex">
                        <a href="#" class="header__btn1 btn btn2">
                            Learn More
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
                        Amet lectus nisl amet, sed ultrices orci sit. Nisl, sodales consectetur at quis dolor. Sagittis,
                        pellentesque blandit nulla leo. Quis nibh vel pellentesque diam cras ac eget posuere. Pellentesque
                        feugiat non egestas senectus malesuada nulla eu. Tellus dolor lectus eu neque, egestas ac. Massa
                        amet, erat euismod erat varius. Nulla nibh nec tortor, mi. Ultricies tincidunt faucibus diam
                        consectetur tempus iaculis adipiscing volutpat. Feugiat sapien et quis massa placerat sollicitudin
                        pulvinar est. Cras et odio tempus commodo placerat nibh molestie eu urna.
                    </p>
                    <p class="about__text">
                        Nisl, at imperdiet massa quis elementum malesuada urna nunc, risus. Congue urna nascetur faucibus
                        pretium facilisis faucibus. Ullamcorper mauris iaculis arcu mattis volutpat pretium. Consectetur
                        duis rhoncus iaculis eget tempus lacinia adipiscing quisque purus. Tincidunt mauris condimentum dui
                        vitae hendrerit sit platea. Porttitor tincidunt proin praesent velit auctor sagittis scelerisque
                        feugiat tortor. Lectus quis posuere aliquet imperdiet molestie elit. Dolor feugiat diam ut odio.
                        Neque, vitae dis amet, faucibus ipsum fermentum orci.
                    </p>
                    <p class="about__text">
                        A vitae egestas adipiscing lectus. Aliquet in varius nunc sit lectus nulla quis. Turpis orci diam
                        velit proin laoreet. Elementum ut egestas duis fringilla dignissim. Id arcu pulvinar nibh massa
                        habitant ut. Tempus, nec aliquam morbi maecenas. Eu, sodales eget ut id egestas dignissim at
                        aliquam, hendrerit. Consectetur accumsan ut congue rutrum. Viverra ultrices lacus, eu fermentum. Ac
                        laoreet imperdiet quis sed sed lobortis. Tristique ultrices turpis volutpat in. Enim, turpis
                        dignissim aliquam aliquet lectus adipiscing nunc aliquam.
                    </p>
                </div>
                <img src="{{ asset('css/img/about.png') }}" alt="" class="about__pic">
            </div>
            <div class="about__wrap">
                <table class="profile__table adaptive-table">
                    <thead>
                    <tr class="profile__row">
                        <td class="active">#</td>
                        <td class="active pad">Name / Last percentage change <a href="#"><img
                                    src="{{ asset('css/img/table/arr1.svg') }}" alt="" class="about__arr svg"></a> </td>
                        <td class="active">Price</td>
                        <td class="active">Rank <a href="#"><img src="{{ asset('css/img/table/arr1.svg') }}" alt=""
                                                                 class="about__arr svg"></a></td>
                        <td class="active">Rank (2d selected in the filter) <a href="#"><img
                                    src="{{ asset('css/img/table/arr1.svg') }}" alt="" class="about__arr svg"></a></td>
                        <td class="active">Rank (30D) <a href="#"><img src="{{ asset('css/img/table/arr1.svg') }}" alt=""
                                                                       class="about__arr svg"></a></td>
                        <td class="active">Rank (60D) <a href="#"><img src="{{ asset('css/img/table/arr1.svg') }}" alt=""
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
                                    <img src="{{ asset('css/img/table/logo1.png') }}" alt="" class="table__logo">
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
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>
{{--                                <a href="#" class="table__icon">--}}
{{--                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">--}}
{{--                                </a>--}}
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">2</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="{{ asset('css/img/table/logo2.png') }}" alt="" class="table__logo">
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
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>
{{--                                <a href="#" class="table__icon able">--}}
{{--                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">--}}
{{--                                </a>--}}
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">3</td>
                        <td class="active green" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="{{ asset('css/img/table/logo3.png') }}" alt="" class="table__logo">
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
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>
{{--                                <a href="#" class="table__icon">--}}
{{--                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">--}}
{{--                                </a>--}}
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">4</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="{{ asset('css/img/table/logo4.png') }}" alt="" class="table__logo">
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
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>
{{--                                <a href="#" class="table__icon">--}}
{{--                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">--}}
{{--                                </a>--}}
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">5</td>
                        <td class="active green" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="{{ asset('css/img/table/logo5.png') }}" alt="" class="table__logo">
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
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>
{{--                                <a href="#" class="table__icon">--}}
{{--                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">--}}
{{--                                </a>--}}
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">6</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="{{ asset('css/img/table/logo6.png') }}" alt="" class="table__logo">
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
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>
{{--                                <a href="#" class="table__icon able">--}}
{{--                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">--}}
{{--                                </a>--}}
                            </div>
                        </td>
                    </tr>
                    <tr class="profile__row">
                        <td class="active" data-label="#">7</td>
                        <td class="active red" data-label="Name">
                            <div class="table__row d-flex">
                                <div class="table__flex d-flex">
                                    <img src="{{ asset('css/img/table/logo7.png') }}" alt="" class="table__logo">
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
                                    <img src="{{ asset('css/img/table/star.svg') }}" alt="" class="svg">
                                </a>
{{--                                <a href="#" class="table__icon able">--}}
{{--                                    <img src="{{ asset('css/img/table/icon.svg') }}" alt="" class="svg">--}}
{{--                                </a>--}}
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <section class="van" id="van">
        <img src="{{ asset('css/img/top.png') }}" alt="" class="van__fig">
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
                        <img src="{{ asset('css/img/icon1.svg') }}" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Nulla in vivamus
                    </p>
                    <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p>
                </div>
                <div class="van__item">
                    <div class="van__block">
                        <img src="{{ asset('css/img/icon2.svg') }}" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Eget eget in
                    </p>
                    <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p>
                </div>
                <div class="van__item">
                    <div class="van__block">
                        <img src="{{ asset('css/img/icon3.svg') }}" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Velit nisl eget
                    </p>
                    <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p>
                </div>
                <div class="van__item">
                    <div class="van__block">
                        <img src="{{ asset('css/img/icon4.svg') }}" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Nulla in vivamus
                    </p>
                    <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p>
                </div>
                <div class="van__item">
                    <div class="van__block">
                        <img src="{{ asset('css/img/icon5.svg') }}" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Nulla in vivamus
                    </p>
                    <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p>
                </div>
                <div class="van__item">
                    <div class="van__block">
                        <img src="{{ asset('css/img/icon6.svg') }}" alt="" class="van__icon">
                    </div>
                    <p class="van__name">
                        Nulla in vivamus
                    </p>
                    <p class="van__text">
                        Id aliquam et purus id elit, ut sed habitasse interdum. Donec amet, consectetur purus mollis nunc
                        cursus ut enim lorem. Blandit urna diam in feugiat egestas eu.
                    </p>
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
            <div class="our__box d-flex">
                <div class="our__item">
                    <p class="our__time">
                        1 Week
                    </p>
                    <p class="our__sum">
                        $3
                    </p>
                    <ul class="our__list">
                        <li>
                            Vote for a project
                        </li>
                        <li>
                            Leave a review about the project
                        </li>
                        <li>
                            Add your own coins
                        </li>
                        <li>
                            Supplement information about tokens
                        </li>
                        <li>
                            Subscribe to push notifications of a trustline open to the user
                        </li>
                        <li>
                            Subscribe to push notifications about new tokens
                        </li>
                    </ul>
                    <a href="#" class="our__btn btn btn2">
                        Buy
                    </a>
                </div>
                <div class="our__item">
                    <p class="our__time">
                        1 Month
                    </p>
                    <p class="our__sum">
                        $10
                    </p>
                    <ul class="our__list">
                        <li>
                            Vote for a project
                        </li>
                        <li>
                            Leave a review about the project
                        </li>
                        <li>
                            Add your own coins
                        </li>
                        <li>
                            Supplement information about tokens
                        </li>
                        <li>
                            Subscribe to push notifications of a trustline open to the user
                        </li>
                        <li>
                            Subscribe to push notifications about new tokens
                        </li>
                    </ul>
                    <a href="#" class="our__btn btn btn2">
                        Buy
                    </a>
                </div>
                <div class="our__item">
                    <p class="our__time">
                        1 Year
                    </p>
                    <p class="our__sum">
                        $100
                    </p>
                    <ul class="our__list">
                        <li>
                            Vote for a project
                        </li>
                        <li>
                            Leave a review about the project
                        </li>
                        <li>
                            Add your own coins
                        </li>
                        <li>
                            Supplement information about tokens
                        </li>
                        <li>
                            Subscribe to push notifications of a trustline open to the user
                        </li>
                        <li>
                            Subscribe to push notifications about new tokens
                        </li>
                    </ul>
                    <a href="#" class="our__btn btn btn2">
                        Buy
                    </a>
                </div>
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
                        Amet lectus nisl amet, sed ultrices orci sit. Nisl, sodales consectetur at quis dolor. Sagittis,
                        pellentesque blandit nulla leo. Quis nibh vel pellentesque diam cras ac eget posuere. Pellentesque
                        feugiat non egestas senectus malesuada nulla eu. Tellus dolor lectus eu neque, egestas ac. Massa
                        amet, erat euismod erat varius. Nulla nibh nec tortor, mi. Ultricies tincidunt faucibus diam
                        consectetur tempus iaculis adipiscing volutpat. Feugiat sapien et quis massa placerat sollicitudin
                        pulvinar est. Cras et odio tempus commodo placerat nibh molestie eu urna.
                    </p>
                    <p class="about__text">
                        Nisl, at imperdiet massa quis elementum malesuada urna nunc, risus. Congue urna nascetur faucibus
                        pretium facilisis faucibus. Ullamcorper mauris iaculis arcu mattis volutpat pretium. Consectetur
                        duis rhoncus iaculis eget tempus lacinia adipiscing quisque purus. Tincidunt mauris condimentum dui
                        vitae hendrerit sit platea. Porttitor tincidunt proin praesent velit auctor sagittis scelerisque
                        feugiat tortor. Lectus quis posuere aliquet imperdiet molestie elit. Dolor feugiat diam ut odio.
                        Neque, vitae dis amet, faucibus ipsum fermentum orci.
                    </p>
                    <p class="about__text">
                        A vitae egestas adipiscing lectus. Aliquet in varius nunc sit lectus nulla quis. Turpis orci diam
                        velit proin laoreet. Elementum ut egestas duis fringilla dignissim. Id arcu pulvinar nibh massa
                        habitant ut. Tempus, nec aliquam morbi maecenas. Eu, sodales eget ut id egestas dignissim at
                        aliquam, hendrerit. Consectetur accumsan ut congue rutrum. Viverra ultrices lacus, eu fermentum. Ac
                        laoreet imperdiet quis sed sed lobortis. Tristique ultrices turpis volutpat in. Enim, turpis
                        dignissim aliquam aliquet lectus adipiscing nunc aliquam.
                    </p>
                </div>
                <img src="{{ asset('css/img/about2.png') }}" alt="" class="about__pic">
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
						05
					</span>
            </div>
            <div class="rev__box d-flex">
                <div class="rev__item">
                    <div class="rev__top d-flex">
                        <img src="{{ asset('css/img/reviews/1.png') }}" alt="" class="rev__ava">
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
                        <img src="{{ asset('css/img/reviews/2.png') }}" alt="" class="rev__ava">
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
                        <img src="{{ asset('css/img/reviews/3.png') }}" alt="" class="rev__ava">
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
                        <img src="{{ asset('css/img/reviews/4.png') }}" alt="" class="rev__ava">
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
                        <img src="{{ asset('css/img/reviews/5.png') }}" alt="" class="rev__ava">
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
                        <img src="{{ asset('css/img/reviews/6.png') }}" alt="" class="rev__ava">
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
						06
					</span>
            </div>
            <div class="accordion-container">
                <div class="set">
                    <a href="#">
                        Enim tortor augue vulputate quisque amet ac nunc at?
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
                        <p>
                            Dignissim diam laoreet turpis penatibus ut. Mauris non et mi, volutpat. Amet, convallis sit
                            faucibus sed nunc. Viverra fringilla non, iaculis augue suspendisse amet ut. Senectus at
                            faucibus phasellus lorem vestibulum eu proin quis congue. Tincidunt tortor amet et nibh. Gravida
                            sem nisi, feugiat ligula egestas mi. Gravida risus ac mauris consectetur nisi est sed lobortis.
                            Dignissim tristique lectus in pulvinar erat odio. Rhoncus mi, nunc leo neque laoreet libero.
                        </p>
                    </div>
                </div>
                <div class="set">
                    <a href="#">
                        Amet et diam bibendum hendrerit aliquam praesent tortor?
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
                        Tellus nulla sed sagittis turpis?
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
                        Tellus nulla sed sagittis turpis?
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
                        Tellus nulla sed sagittis turpis?
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
                        Tellus nulla sed sagittis turpis?
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
                        Tellus nulla sed sagittis turpis?
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
        <!-- <img src="{{ asset('css/img/footer/footer.png') }}" alt="" class="footer__bg"> -->
        <div class="container">
            <div class="footer__box">
                <div class="footer__flex d-flex">
                    <a href="#" class="footer__logo">
                        <img src="{{ asset('css/img/footer/logo.svg') }}" alt="">
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
</div>
<script src="//yandex.st/jquery/1.9.1/jquery.min.js"></script>
<script src="{{ asset('/js/all.js') }}"></script>
</body>

</html>
