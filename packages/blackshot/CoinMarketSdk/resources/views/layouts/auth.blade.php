<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css?v=06132022') }}" rel="stylesheet">

    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&family=Merriweather:ital,wght@1,300;1,700;1,900&display=swap"
        rel="stylesheet">

</head>
<body>
    <div class="body-wrap pages">
        <header class="header header-pages" id="header-pages">
            @include('blackshot::layouts.navigation')
        </header>

        <main>
            @include('blackshot::layouts.breadcrumbs')

            <div class="container">
                @yield('content')
            </div>
        </main>

        @include('blackshot::layouts.footer')
    </div>
    @stack('scripts')
</body>
</html>
