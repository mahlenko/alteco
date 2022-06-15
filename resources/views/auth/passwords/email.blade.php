@extends('blackshot::layouts.auth')

@section('content')
    <div class="auth-container">
        <div class="login-container">
            <h1>Сброс пароля</h1>

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

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-control-row">
                    <label for="email">{{ __('E-mail') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                    <p class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </p>
                    @enderror
                </div>

                <button type="submit" class="btn btn1" style="width: 100%">
                    {{ __('Отправить ссылку на сброс пароля') }}
                </button>
            </form>
        </div>
    </div>
@endsection
