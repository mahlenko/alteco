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

                <a href="javascript:void(0);" class="btn btn1" onclick="return AppDialog.show({{ $tariff->id }})">Купить доступ</a>
            </div>

            <div class="dialog" data-dialog-id="{{ $tariff->id }}">
                <div class="dialog__container">
                    <a href="#" data-dialog-el="close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m-15 0l15 15" />
                        </svg>
                    </a>

                    {!! $tariff->payment_widget !!}
                </div>
            </div>
        @endforeach
    </div>
@endsection
