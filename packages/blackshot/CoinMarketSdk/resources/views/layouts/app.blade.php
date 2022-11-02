<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ mix('css/main.css') }}" rel="stylesheet">
{{--    <link rel="stylesheet" href="{{ asset('css/jquery.arcticmodal-0.3.css') }}">--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
{{--    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/pikaday/css/pikaday.css">--}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;600;700;800&family=Merriweather:ital,wght@1,300;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jquery.arcticmodal-0.3.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.14.0/css/selectize.bootstrap5.css" integrity="sha512-QomP/COM7vFCHcVHpDh/dW9oDyg44VWNzgrg9cG8T2cYSXPtqkQK54WRpbqttfo0MYlwlLUz3EUR+78/aSbEIw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <div class="body-wrap pages">
        <header class="header header-pages" id="header-pages">
            @include('blackshot::layouts.navigation')
        </header>

        <main>
            @include('blackshot::layouts.breadcrumbs')

            <section class="scan" id="scan">
                <div class="container">
                    @hasSection('title')
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <div>
                                <h1>@yield('title')</h1>
                            </div>

                            @yield('title-extend')
                        </div>
                    @endif

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
            </section>
        </main>

        @include('blackshot::layouts.footer')
    </div>

{{--    <script src="https://kit.fontawesome.com/abad78b0dd.js" crossorigin="anonymous"></script>--}}

    <!-- Scripts -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <script src="{{ asset('js/jquery.arcticmodal-0.3.min.js') }}"></script>
    <script src="{{ asset('js/all.js') }}" defer></script>

    @stack('scripts')

    <script src="{{ mix('js/app.js') }}" defer></script>

    <script>
        $(document).ready(() => {
            // $('select').selectize()

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


        function singleDatepicker(input)
        {
            $(input).daterangepicker({
                "singleDatePicker": true,
                "autoApply": true,
                "locale": {
                    "format": "DD.MM.YYYY",
                    "separator": " - ",
                    "applyLabel": "Применить",
                    "cancelLabel": "Отменить",
                    "fromLabel": "Начало",
                    "toLabel": "Конец",
                    "customRangeLabel": "Другое",
                    "weekLabel": "Н",
                    "daysOfWeek": ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
                    "monthNames": ["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
                    "firstDay": 1
                },
                "drops": "up"
            }, function(start, end, label) {
                console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
            });
        }


    </script>
</body>
</html>
