<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.bootstrap4.min.css" rel="stylesheet">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-blackshot navbar-expand-md bg-light border-bottom">
            <div class="container">
                <div class="d-flex">
                    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
{{--                        <i class="fab fa-bitcoin fa-2x me-2"></i>--}}
{{--                        {{ config('app.name', 'Laravel') }}--}}
                        <picture>
                            <source type="image/webp" srcset="{{ asset('images/logotype.webp?v=1.0') }}">
                            <img src="{{ asset('images/logotype.png?v=1.0') }}" alt="" style="height: 70px">
                        </picture>
                    </a>

{{--                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">--}}
{{--                        <span class="navbar-toggler-icon"></span>--}}
{{--                    </button>--}}
                </div>

                <div class="collapse navbar-collapse d-flex justify-content-center justify-content-md-end" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
{{--                    <ul class="navbar-nav mr-auto">--}}
{{--                    </ul>--}}

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav d-flex flex-row">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i>
                                        {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i>
                                        {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a href="{{ route('signals.home') }}" class="nav-link">
                                    <i class="fas fa-chess-queen"></i>
                                    Signals
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('coins.home') }}" class="nav-link">
                                    <i class="fab fa-bitcoin"></i>
                                    Coins
                                </a>
                            </li>
                            @if (\Illuminate\Support\Facades\Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a href="{{ route('users.home') }}" class="nav-link">
                                    <i class="fas fa-users"></i>
                                    Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('settings.home') }}" class="nav-link">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                            </li>
                            @endif

                            <li class="nav-item dropdown" style="position:relative;">
                                <a id="navbarDropdown" class="nav-link no-after dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="far fa-user-circle"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div style="position:absolute;" class="dropdown-menu dropdown-menu-macos dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.edit', \Illuminate\Support\Facades\Auth::id()) }}">
                                        <i class="far fa-user"></i>
                                        {{ __('Profile') }}
                                    </a>

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @if (Breadcrumbs::exists(Route::current()->getName()))
            <div class="bg-light mb-4">
                <div class="container">
                    {{ Breadcrumbs::render(Route::current()->getName(), get_defined_vars()['__data'] ?? []) }}
                </div>
            </div>
            @endif

            <div class="container">
                @include('flash::message')
                @if ($errors->count())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://kit.fontawesome.com/abad78b0dd.js" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    @stack('scripts')

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

    <script>
        $(document).ready(() => {
            $('input[data-type="datepicker"]').daterangepicker({
                ranges: {
                    'Last 1 Day': [moment().subtract(1, 'day'), moment()],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'Last 60 Days': [moment().subtract(59,'days'), moment()],
                },
                alwaysShowCalendars: true,
                autoApply: true,
            }).on('apply.daterangepicker', (ev, picker) => {
                $(picker.element)
                    // .attr('disabled', true)
                    .parents('form').submit()
            });
        })
    </script>
</body>
</html>
