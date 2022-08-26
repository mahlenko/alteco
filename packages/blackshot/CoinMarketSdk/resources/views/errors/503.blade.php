@extends('blackshot::layouts.app')

@section('content')
    <div class="d-flex" style="
        padding: 5rem;
        align-content: center;
        justify-content: center;
        flex-direction: column;">
            <h2>Доступ закрыт</h2>
            <p style="display: flex; flex-direction: column; align-items: center; row-gap: 1rem; text-align: center;">
                {{ $exception->getMessage() ?? 'Возможно произошла ошибка, попробуйте позже.' }}<br>
                <a href="{{ route('subscribe') }}" class="btn btn1" style="display: inline-flex">Выбрать тариф</a>
            </p>
    </div>
@endsection
