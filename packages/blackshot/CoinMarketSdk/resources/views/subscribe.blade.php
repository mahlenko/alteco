@extends('blackshot::layouts.app')

@section('content')
    <h1>Продление подписки</h1>

    <div class="tariffs__container">
        @foreach($tariffs as $tariff)
            <div class="item">
                <div class="item__title">
                    <span class="item__name">{{ $tariff->name }}</span>
                    <span class="item__amount">
                        {{ $tariff->amount }} руб. / {{ $tariff->days }} {{ trans_choice('день|дня|дней', $tariff->days) }}
                    </span>
                </div>

                <span class="item__description">{!! $tariff->description !!}</span>

                <a href="javascript:void(0);" class="btn btn1" onclick="return alert('Ожидаем скрипты виджетов, для подключения.')">Купить доступ</a>
            </div>
        @endforeach
    </div>
@endsection
