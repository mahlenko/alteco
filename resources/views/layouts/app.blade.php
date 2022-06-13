<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-blackshot navbar-expand-md navbar-dark bg-dark text-white">
            <div class="container">
                <div class="d-flex">
                    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                        <i class="fab fa-bitcoin fa-2x me-2"></i>
                        {{ config('app.name', 'Laravel') }}
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
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a href="{{ route('coins.home') }}" class="nav-link">
                                    <i class="fab fa-bitcoin"></i>
                                    Coins
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-chess-queen"></i>
                                    Signals
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.home') }}" class="nav-link">
                                    <i class="fas fa-users"></i>
                                    Users
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-cog"></i>
                                    Settings
                                </a>
                            </li>
                            <li class="nav-item dropdown" style="position:relative;">
                                <a id="navbarDropdown" class="nav-link no-after dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <i class="far fa-user-circle"></i>
                                    {{ Auth::user()->name }}
                                </a>

                                <div style="position:absolute;" class="dropdown-menu dropdown-menu-macos dropdown-menu-dark dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
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
            @yield('content')
        </main>
    </div>

{{--    <script src="https://kit.fontawesome.com/abad78b0dd.js" crossorigin="anonymous"></script>--}}
</body>
</html>
